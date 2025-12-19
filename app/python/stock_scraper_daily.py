import cloudscraper
import json
import sys
from datetime import datetime
import time


# Daftar saham populer IDX (sama dengan file original)
STOCKS = {
    'BBCA': 'Bank Central Asia Tbk',
    'BBRI': 'Bank Rakyat Indonesia Tbk',
    'BMRI': 'Bank Mandiri Tbk',
    'BBNI': 'Bank Negara Indonesia Tbk',
    'TLKM': 'Telkom Indonesia Tbk',
    'ASII': 'Astra International Tbk',
    'UNVR': 'Unilever Indonesia Tbk',
    'ICBP': 'Indofood CBP Sukses Makmur Tbk',
    'GGRM': 'Gudang Garam Tbk',
    'INDF': 'Indofood Sukses Makmur Tbk',
    'KLBF': 'Kalbe Farma Tbk',
    'HMSP': 'HM Sampoerna Tbk',
    'ADRO': 'Adaro Energy Indonesia Tbk',
    'ANTM': 'Aneka Tambang Tbk',
    'PTBA': 'Bukit Asam Tbk',
    'SMGR': 'Semen Indonesia Tbk',
    'INTP': 'Indocement Tunggal Prakarsa Tbk',
    'ACES': 'Ace Hardware Indonesia Tbk',
    'MAPI': 'Mitra Adiperkasa Tbk',
    'ERAA': 'Erajaya Swasembada Tbk'
}

SECTOR_MAPPING = {
    'BBCA': 'Perbankan',
    'BBRI': 'Perbankan',
    'BMRI': 'Perbankan',
    'BBNI': 'Perbankan',
    'TLKM': 'Telekomunikasi',
    'ASII': 'Otomotif',
    'UNVR': 'Konsumer',
    'ICBP': 'Konsumer',
    'GGRM': 'Konsumer',
    'INDF': 'Konsumer',
    'KLBF': 'Kesehatan',
    'HMSP': 'Konsumer',
    'ADRO': 'Energi',
    'ANTM': 'Pertambangan',
    'PTBA': 'Energi',
    'SMGR': 'Infrastruktur',
    'INTP': 'Infrastruktur',
    'ACES': 'Retail',
    'MAPI': 'Retail',
    'ERAA': 'Teknologi'
}


def get_stock_data(code, name, http, days=1):
    """
    Scrape data saham dari IDX (atau fallback Yahoo) untuk N hari terakhir.
    Returns a list of day dicts (same shape as previous single-day dicts).
    """
    # Try IDX endpoints with length parameter
    endpoints = [
        f"https://www.idx.co.id/umbraco/Surface/ListedCompany/GetTradingInfoSS?code={code}&length={days}",
        f"https://idx.co.id/umbraco/Surface/ListedCompany/GetTradingInfoSS?code={code}&length={days}",
        f"https://www.idx.co.id/id-id/umbraco/Surface/ListedCompany/GetTradingInfoSS?code={code}&length={days}"
    ]

    result = None
    successful_endpoint = None

    for idx_ep, endpoint in enumerate(endpoints, 1):
        try:
            response = http.get(endpoint, timeout=30)
            if response.status_code == 200:
                result = json.loads(response.text)
                if result.get("replies") and len(result["replies"]) > 0:
                    successful_endpoint = idx_ep
                    print(f" ✓ Endpoint {idx_ep} success", file=sys.stderr)
                    break
        except Exception:
            continue

    # Try to parse IDX response into array of day dicts
    if result and result.get('replies'):
        reply = result['replies'][0]
        # If reply contains a list of trading items, find it
        series = None
        if isinstance(reply, dict):
            # look for first list-of-dict field
            for k, v in reply.items():
                if isinstance(v, list) and len(v) > 0 and isinstance(v[0], dict):
                    series = v
                    break
            # fallback: if reply itself looks like a single-day dict, wrap it
            if series is None:
                # Detect fields commonly present for a single item
                if any(k in reply for k in ['Date', 'OpenPrice', 'Close', 'Open', 'High', 'Low']):
                    series = [reply]
        elif isinstance(reply, list):
            series = reply

        if series:
            out = []
            for item in series:
                try:
                    eps = None
                    per = None
                    roe = None
                    # IDX single item keys
                    tanggal = convert_date(item.get('Date') or item.get('date') or item.get('Tanggal') or '')
                    open_p = item.get('OpenPrice') or item.get('Open') or item.get('open') or 0
                    high = item.get('High') or item.get('high') or 0
                    low = item.get('Low') or item.get('low') or 0
                    close = item.get('Close') or item.get('close') or item.get('ClosePrice') or 0
                    volume = item.get('Volume') or item.get('volume') or 0

                    out.append({
                        'tanggal': tanggal,
                        'kode_saham': code.upper(),
                        'nama_saham': name,
                        'sektor': SECTOR_MAPPING.get(code, 'Lainnya'),
                        'harga_buka': float(open_p),
                        'harga_tertinggi': float(high),
                        'harga_terendah': float(low),
                        'harga_tutup': float(close),
                        'volume': int(volume),
                        'eps': eps,
                        'per': per,
                        'roe': roe
                    })
                except Exception as e:
                    print(f" ✗ Parse item error: {str(e)}", file=sys.stderr)
                    continue

            if len(out) > 0:
                return out

    # If IDX failed or returned nothing usable, fallback to Yahoo
    print(f" ⚠️ IDX failed or no series, trying Yahoo Finance for {code} ({days}d)", file=sys.stderr)
    try:
        import yfinance as yf
        ticker = f"{code}.JK"
        stock = yf.Ticker(ticker)
        # Yahoo: use history to fetch the last `days` rows
        period = f"{days}d" if isinstance(days, int) and days > 1 else '1d'
        hist = stock.history(period=period)

        if hist is None or hist.empty:
            return None

        out = []
        info = {}
        try:
            info = stock.info or {}
        except Exception:
            info = {}

        eps_val = info.get('trailingEps') if info.get('trailingEps') is not None else None
        per_val = info.get('trailingPE') if info.get('trailingPE') is not None else None
        roe_val = info.get('returnOnEquity')
        roe_pct = None
        if roe_val is not None:
            try:
                roe_pct = round(float(roe_val) * 100, 2)
            except Exception:
                roe_pct = None

        for idx_row in range(len(hist)):
            row = hist.iloc[idx_row]
            try:
                d = row.name.to_pydatetime().strftime('%Y-%m-%d')
            except Exception:
                d = datetime.now().strftime('%Y-%m-%d')

            out.append({
                'tanggal': d,
                'kode_saham': code.upper(),
                'nama_saham': name,
                'sektor': SECTOR_MAPPING.get(code, 'Lainnya'),
                'harga_buka': float(row['Open']),
                'harga_tertinggi': float(row['High']),
                'harga_terendah': float(row['Low']),
                'harga_tutup': float(row['Close']),
                'volume': int(row['Volume']) if 'Volume' in row and not (row['Volume'] is None) else 0,
                'eps': eps_val,
                'per': per_val,
                'roe': roe_pct
            })

        return out

    except Exception as e:
        print(f" ✗ Yahoo Finance error: {str(e)}", file=sys.stderr)

    return None


def convert_date(tanggal_str):
    """Konversi format tanggal ke MySQL format (YYYY-MM-DD)"""
    if not tanggal_str:
        return datetime.now().strftime('%Y-%m-%d')
    
    formats = ['%d %b %Y', '%d/%m/%Y', '%Y-%m-%d', '%d-%m-%Y']
    
    for fmt in formats:
        try:
            tanggal_obj = datetime.strptime(tanggal_str.strip(), fmt)
            return tanggal_obj.strftime('%Y-%m-%d')
        except ValueError:
            continue
    
    return datetime.now().strftime('%Y-%m-%d')


def get_yfinance_fallback(code, name):
    """Fallback: Ambil data dari Yahoo Finance jika IDX gagal"""
    try:
        import yfinance as yf
        
        ticker = f"{code}.JK"
        stock = yf.Ticker(ticker)
        hist = stock.history(period='1d')
        
        if not hist.empty:
            last_row = hist.iloc[-1]
            print(f" ✓ Yahoo Finance success", file=sys.stderr)

            # Try to read financial ratios from Ticker.info
            eps = None
            per = None
            roe = None
            try:
                info = stock.info
                eps = info.get('trailingEps') if info.get('trailingEps') is not None else None
                per = info.get('trailingPE') if info.get('trailingPE') is not None else None
                roe_val = info.get('returnOnEquity')
                if roe_val is not None:
                    try:
                        roe = round(float(roe_val) * 100, 2)
                    except Exception:
                        roe = None
            except Exception as e:
                # ignore
                pass

            return {
                'tanggal': datetime.now().strftime('%Y-%m-%d'),
                'kode_saham': code.upper(),
                'nama_saham': name,
                'sektor': SECTOR_MAPPING.get(code, 'Lainnya'),
                'harga_buka': float(last_row['Open']),
                'harga_tertinggi': float(last_row['High']),
                'harga_terendah': float(last_row['Low']),
                'harga_tutup': float(last_row['Close']),
                'volume': int(last_row['Volume']),
                'eps': eps,
                'per': per,
                'roe': roe
            }
            
    except Exception as e:
        print(f" ✗ Yahoo Finance error: {str(e)}", file=sys.stderr)
    
    return None


def scrape_single_stock(code, days=1):
    """
    Scrape 1 saham saja - kompatibel dengan PHP system

    Returns: if days==1, 'data' is a dict (as before). If days>1, 'data' is a list of dicts.
    """
    code = code.upper()

    # Validasi kode saham
    if code not in STOCKS:
        return {
            'success': False,
            'error': f'Stock code {code} not found in list. Available: {", ".join(STOCKS.keys())}'
        }

    # Create scraper
    http = cloudscraper.create_scraper()
    name = STOCKS[code]

    # Scrape data
    data = get_stock_data(code, name, http, days=days)

    if data:
        # If days==1, return single dict for backward compatibility
        if isinstance(data, list) and len(data) == 1 and days == 1:
            return {
                'success': True,
                'data': data[0]
            }
        return {
            'success': True,
            'data': data
        }
    else:
        return {
            'success': False,
            'error': f'Failed to scrape data for {code}. All endpoints returned no data.'
        }


def scrape_all_stocks(delay=2, days=30):
    """
    Scrape semua saham dalam daftar for the last `days` days
    """
    print("=" * 70, file=sys.stderr)
    print(f" IDX {days}-DAY STOCK SCRAPER - ALL STOCKS", file=sys.stderr)
    print("=" * 70, file=sys.stderr)
    print(f"\nTotal stocks: {len(STOCKS)}", file=sys.stderr)
    print(f"Delay per request: {delay} seconds", file=sys.stderr)
    print(f"Estimated time: ~{(len(STOCKS) * delay) / 60:.1f} minutes\n", file=sys.stderr)
    print("=" * 70, file=sys.stderr)

    http = cloudscraper.create_scraper()

    results = []
    success_count = 0
    failed_count = 0

    for idx, (code, name) in enumerate(STOCKS.items(), 1):
        print(f"\n[{idx}/{len(STOCKS)}] Processing {code} - {name}", file=sys.stderr)

        data_list = get_stock_data(code, name, http, days=days)

        if data_list:
            # data_list may be list of days
            if isinstance(data_list, list):
                for d in data_list:
                    results.append(d)
            else:
                results.append(data_list)

            success_count += 1
            last_price = None
            try:
                last_price = results[-1]['harga_tutup']
            except Exception:
                last_price = None
            print(f" ✓ Success: {last_price if last_price is not None else 'N/A'}", file=sys.stderr)
        else:
            failed_count += 1
            print(f" ✗ Failed", file=sys.stderr)

        if idx < len(STOCKS):
            print(f" ⏳ Waiting {delay} seconds...", file=sys.stderr)
            time.sleep(delay)

    print("\n" + "=" * 70, file=sys.stderr)
    print(" SCRAPING COMPLETED", file=sys.stderr)
    print("=" * 70, file=sys.stderr)
    print(f"Success: {success_count} stocks | Failed: {failed_count} stocks", file=sys.stderr)
    print("=" * 70 + "\n", file=sys.stderr)

    return {
        'success': True,
        'timestamp': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
        'total_stocks': len(STOCKS),
        'success_count': success_count,
        'failed_count': failed_count,
        'data': results
    }


if __name__ == "__main__":
    # Command-line usage:
    # python stock_scraper_daily.py                -> batch mode, last 30 days
    # python stock_scraper_daily.py BBCA           -> single latest day for BBCA
    # python stock_scraper_daily.py BBCA --days 30 -> single stock, last 30 days
    # python stock_scraper_daily.py --days 30      -> batch mode, last 30 days

    days = 30
    if len(sys.argv) > 1:
        # parse args
        args = sys.argv[1:]
        code = None
        # Simple parser for --days or -d
        i = 0
        while i < len(args):
            a = args[i]
            if a in ('--days', '-d') and i + 1 < len(args):
                try:
                    days = int(args[i + 1])
                except Exception:
                    days = 30
                i += 2
            elif a.startswith('--days='):
                try:
                    days = int(a.split('=', 1)[1])
                except Exception:
                    days = 30
                i += 1
            else:
                # assume first positional arg is code
                if code is None:
                    code = a.upper()
                i += 1

        if code:
            result = scrape_single_stock(code, days=days)
        else:
            result = scrape_all_stocks(delay=2, days=days)
    else:
        # Default batch mode: last 30 days
        result = scrape_all_stocks(delay=2, days=days)

    print(json.dumps(result, ensure_ascii=False, indent=2))
