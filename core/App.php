<?php

/**
 * Class App
 * Router utama aplikasi untuk menangani URL dan memanggil controller
 */
class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();

        // Cek apakah $url[0] adalah controller atau method
        if (isset($url[0])) {
            // Ubah format url ke format Controller (capitalize + Controller suffix)
            $controllerName = ucfirst($url[0]) . 'Controller';

            // Cek apakah ada file controller dengan nama ini
            if (file_exists(APP_PATH . 'controllers/' . $controllerName . '.php')) {
                // Ini adalah controller
                $this->controller = $controllerName;
                unset($url[0]);
            } else {
                // Bukan controller, mungkin method dari HomeController
                // Load HomeController dan cek apakah ini method
                require_once APP_PATH . 'controllers/' . $this->controller . '.php';
                $tempController = new $this->controller;

                if (method_exists($tempController, $url[0])) {
                    // Ini adalah method
                    $this->method = $url[0];
                    unset($url[0]);
                }
            }
        }

        // Load controller jika belum di-load
        if (!class_exists($this->controller)) {
            require_once APP_PATH . 'controllers/' . $this->controller . '.php';
        }
        $this->controller = new $this->controller;

        // Cek method di url[1] (untuk format Controller/method)
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Cek params
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // Jalankan controller & method, serta kirimkan params jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}
