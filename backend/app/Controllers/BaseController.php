<?php

namespace App\Controllers;

use App\Libraries\CI4Request;
use App\Libraries\CI4Session;
use App\Libraries\CI4Validation;
use Config\Database;
use Config\Services;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    /** @var CI4Request */
    protected $request;
    /** @var CI4Session */
    protected $session;
    /** @var CI4Validation */
    protected $validation;
    protected $db;
    protected $helpers = ['Global'];

    public function __construct()
    {
        $this->ensureServices();
    }

    public function initController($request = null, $response = null, $logger = null)
    {
        $this->ensureServices();
    }

    protected function ensureServices()
    {
        if (!$this->request) {
            $this->request = new CI4Request();
        }
        if (!$this->session) {
            $this->session = Services::session();
        }
        if (!$this->validation) {
            $this->validation = Services::validation();
        }
        if (!$this->db) {
            $this->db = Database::connect();
        }
    }

    public function __get($name)
    {
        if ($name === 'request') {
            if (!$this->request) $this->request = new CI4Request();
            return $this->request;
        }
        if ($name === 'session') {
            if (!$this->session) $this->session = Services::session();
            return $this->session;
        }
        if ($name === 'validation') {
            if (!$this->validation) $this->validation = Services::validation();
            return $this->validation;
        }
        if ($name === 'db') {
            if (!$this->db) $this->db = Database::connect();
            return $this->db;
        }
        return null;
    }

    public function callAction($method, $parameters)
    {
        $this->ensureServices();

        ob_start();
        $response = $this->{$method}(...array_values($parameters));
        $output = ob_get_clean();

        if ($response instanceof Response) {
            return $response;
        }

        if (!empty($output)) {
            return response($output);
        }

        if ($response !== null) {
            return response($response);
        }

        return response('');
    }
}
