<?php

/**
 * Coinbase Exchange - PRO - API
 *
 * https://docs.cloud.coinbase.com/exchange/reference
 */
class CoinbaseExchange {

    /**
     * API Endpoint URL
     */
    public $url = 'https://api.exchange.coinbase.com';

    /**
     * An array of API endpoints
     */
    public $endpoints = array(
        'accounts' => array('method' => 'GET', 'uri' => '/accounts'),
        'account' => array('method' => 'GET', 'uri' => '/accounts/%s'),
        'ledger' => array('method' => 'GET', 'uri' => '/accounts/%s/ledger'),
        'holds' => array('method' => 'GET', 'uri' => '/accounts/%s/holds'),
        'place' => array('method' => 'POST', 'uri' => '/orders'),
        'cancel' => array('method' => 'DELETE', 'uri' => '/orders/%s'),
        'orders' => array('method' => 'GET', 'uri' => '/orders'),
        'order' => array('method' => 'GET', 'uri' => '/orders/%s'),
        'fills' => array('method' => 'GET', 'uri' => '/fills'),
        'products' => array('method' => 'GET', 'uri' => '/products'),
        'book' => array('method' => 'GET', 'uri' => '/products/%s/book'),
        'ticker' => array('method' => 'GET', 'uri' => '/products/%s/ticker'),
        'trades' => array('method' => 'GET', 'uri' => '/products/%s/trades'),
        'stats' => array('method' => 'GET', 'uri' => '/products/%s/stats'),
        'rates' => array('method' => 'GET', 'uri' => '/products/%s/candles'),
        'currencies' => array('method' =>'GET', 'uri' =>  '/currencies'),
        'time' => array('method' => 'GET', 'uri' => '/time'),
        'crypto-accounts' => array('method' => 'GET', 'uri' => '/coinbase-accounts'),
        'create-crypto-address' => array('method' => 'POST', 'uri' => '/coinbase-accounts/%s/addresses'),
        'transaction-crypto-address' => array('method' => 'GET', 'uri' => '/accounts/78e6166a-717c-5beb-b095-043601d66f30/addresses/%s'),
        'deposit-coinbase' => array('method' => 'POST', 'uri' => '/deposits/coinbase-account'),
        'profiles' => array('method' => 'GET', 'uri' => '/profiles'),
        'transfer-funds' => array('method' => 'POST', 'uri' => '/profiles/transfer'),
    );

    /**
     * Headers to send with each call
     */
    public $key = null;

    /**
     * Headers to send with each call
     */
    public $passphrase = null;

    /**
     * Headers to send with each call
     */
    public $timestamp = null;

    /**
     * The secret to sign each call with
     */
    public $secret = null;

    public function auth($key, $passphrase, $secret, $isSandbox = false) {
        $this->key = $key;
        $this->passphrase = $passphrase;
        $this->secret = $secret;
        if($isSandbox) {
            $this->url = 'https://api-public.sandbox.exchange.coinbase.com';
        }
    }

    /**
     * GET /profiles
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_getprofiles
     */
    public function listProfiles($activeOnly = 'both') {
        if($activeOnly == 'both') {
            return $this->request('profiles');
        } else {
            $activeOnly = (bool) $activeOnly;
            return $this->request('profiles', ['active' => $activeOnly]);
        }
    }

    /**
     * GET /deposits/coinbase-account
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_postdepositcoinbaseaccount
     */
    public function depositCoinbase($account_id, $currency, $amount, $profile_id = null) {
        $params = ['coinbase_account_id' => $account_id, 'amount' => $amount, 'currency' => $currency];
        if($profile_id != null) { $params['profile_id'] = $profile_id; }
        return $this->request('deposit-coinbase', $params);
    }

    /**
     * GET /coinbase-accounts
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_getcoinbaseaccounts
     */
    public function listCryptoAccounts() {
        return $this->request('crypto-accounts');
    }

    /**
     * POST /coinbase-accounts/<account-id>/addresses
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_postcoinbaseaccountaddresses
     */
    public function createCryptoAddress($id) {
        return $this->request('create-crypto-address', array('id' => $id));
    }

    /**
     * GET /accounts
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_getaccounts
     */
    public function listAccounts() {
        return $this->request('accounts');
    }

    /**
     * GET /accounts/<account-id>
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_getaccount
     */
    public function getAccount($id) {
        return $this->request('account', array('id' => $id));
    }

    /**
     * GET /accounts/<account-id>/ledger
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_getaccountledger
     */
    public function getAccountHistory($id) {
        return $this->request('ledger', array('id' => $id));
    }

    /**
     * GET /accounts/<account_id>/holds
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_getaccountholds
     */
    public function getHolds($id) {
        return $this->request('holds', array('id' => $id));
    }

    /**
     * POST /orders
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_postorders
     */
    public function placeOrder($side, $price, $size, $productId) {
        $data = array(
            //'client_oid' => '', // client generated UUID
            'price' => $price, // in quote_increment units (0.01 min for BTC-USD)
            'size' => $size, // must honor base_min_size and base_max_size
            'side' => $side, // buy or sell
            'product_id' => $productId
            //'stp' => 'dc' // Or one of co, cn, cb
        );
        return $this->request('place', $data);
    }

    /**
     * DELETE /orders/<order-id>
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_deleteorder
     */
    public function cancelOrder($id) {
        return $this->request('cancel', array('id' => $id));
    }

    /**
     * GET /orders
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_getorders
     */
    public function listOrders() {
        return $this->request('orders');
    }


    /**
     * GET /orders/<order-id>
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_getorder
     */
    public function getOrder($id) {
        return $this->request('order', array('id' => $id));
    }

    /**
     * GET /fills
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_getfills
     */
    public function listFills() {
        return $this->request('fills');
    }

    /**
     * POST /profiles/transfer
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_postprofiletransfer
     */
    public function transferFunds($from, $to, $currency, $amount) {
        return $this->request('transfer-funds', ['from' => $from, 'to' => $to, 'currency' => $currency, 'amount' => $amount]);
    }

    /**
     * GET /products
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_getproducts
     */
    public function listProducts() {
        return $this->request('products');
    }


    /**
     * GET /products/<product-id>/book
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_getproductbook
     */
    public function getOrderBook($product = 'BTC-USD') {
        //$this->validate('product', $product);
        return $this->request('book', array('id' => $product));
    }


    /**
     * GET /products/<product-id>/ticker
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_getproductticker
     */
    public function getTicker($product = 'BTC-USD') {
        return $this->request('ticker', array('id' => $product));
    }

    /**
     * GET /products/<product-id>/trades
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_getproducttrades
     */
    public function listTrades($product = 'BTC-USD') {
        return $this->request('trades', array('id' => $product));
    }

    /**
     * GET /products/<product-id>/candles
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_getproductcandles
     */
    public function getHistoricRates($product = 'BTC-USD') {
        return $this->request('rates', array('id' => $product));
    }

    /**
     * GET /products/<product-id>/stats
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_getproductstats
     */
    public function get24hrStats($product = 'BTC-USD') {
        return $this->request('stats', array('id' => $product));
    }

    /**
     * GET /currencies
     *
     * https://docs.cloud.coinbase.com/exchange/reference/exchangerestapi_getcurrencies
     */
    public function listCurrencies() {
        return $this->request('currencies');
    }

    /**
     * GET /time
     *
     * https://docs.cloud.coinbase.com
     */
    public function getTime() {
        return $this->request('time');
    }

    protected function request($endpoint, $params = array()) {
        extract($this->getEndpoint($endpoint, $params));
        $url = $this->url . $uri;
        $body = (!empty($params) ? json_encode($params) : '');
        $headers = array(
            'User-Agent: CoinbaseExchangePHP/v0.1',
            'Content-Type: application/json',
            'CB-ACCESS-KEY: ' . $this->key,
            'CB-ACCESS-SIGN: ' . $this->sign($method . $uri . $body),
            'CB-ACCESS-TIMESTAMP: ' .  $this->timestamp,
            'CB-ACCESS-PASSPHRASE: ' . $this->passphrase,
        );

        $request = new CoinbaseExchange_Request;
        try {
            $response = $request->call($url, $method, $headers, $body);
            if ($response['statusCode'] === 200) {
                return json_decode($response['body'], true);
            }
            return $response;
        } catch (Exception $e) {
            return 'Caught exception: ' . $e->getMessage();
        }
    }

    protected function getEndpoint($key, $params) {
        $endpoint = $this->endpoints[$key];
        if (empty($endpoint)) {
            throw new Exception('Invalid endpoint ' . $key . ' specified');
        }
        if (!empty($params['id'])) {
            $endpoint['uri'] = sprintf($endpoint['uri'], $params['id']);
            unset($params['id']);
        }
        $endpoint['params'] = $params;
        return $endpoint;
    }

    protected function sign($data) {
        $this->timestamp = time();
        return base64_encode(hash_hmac(
            'sha256',
            $this->timestamp . $data,
            base64_decode($this->secret),
            true
        ));
    }
}
