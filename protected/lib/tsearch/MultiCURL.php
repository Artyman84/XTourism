<?php
/**
 * An easy-to-use wrapper around PHP's curl_multi_* functions, which allows
 * the parallel download of files.
 *
 * This class will makes it easier to start using parallel downloads - which
 * can sometimes significantly speed up the time your script takes to run;
 * especially if it is doing downloading.
 *
 * @author Geoff Garbers <geoff@garbers.co.za>
 * @link https://bitbucket.org/garbetjie/multi-curl/
 * @version 1.0
 */

namespace TSearch;

class MultiCURL {

    /**
     * @var array The list of default settings for curl_setopt()
     */
    private static $options = [

        // собственный метод запроса
        CURLOPT_CUSTOMREQUEST => 'GET',

        // сбросить метод HTTP-запроса в метод GET
        CURLOPT_HTTPGET => true,

        // не следовать за редиректами
        CURLOPT_FOLLOWLOCATION => 0,

        // возвращать результат
        CURLOPT_RETURNTRANSFER => 1,

        // не возвращать http-заголовок
        CURLOPT_HEADER => false,

        // таймаут соединения
        CURLOPT_CONNECTTIMEOUT => 45,

        // таймаут ожидания
        CURLOPT_TIMEOUT => 45,
    ];

    /**
     * The array of cURL requests to make. This will contain the callback, as
     * well as the array of options.
     *
     * @access private
     * @var array
     */
    private $requests = array();


    /**
     * Adds a new cURL resource to the queue to be executed.
     *
     * Allows the adding of another to be made, along with its associated callback.
     * Callbacks can be in any form - function, method, anonymous function, etc
     * as long as it returns true to is_callable().
     *
     * Callbacks will receive two arguments when called: the content of response,
     * and the info array returned by curl_getinfo().
     *
     * @access public
     * @param string $url The cURL url
     * @param mixed $callback Optional callback to have called once execution is finished.
     * @param array $options The cURL options to pass when creating the resource.
     */
    public function add ($url, $callback, $options=[]) {

        // Create resource.
        $ch = curl_init();
        $options = $options + self::$options;
        $options[CURLOPT_URL] = $url;

        curl_setopt_array($ch, (array)$options);

        // This is done in order to prevent multiple loops when calling
        // callbacks.
        $this->requests[$this->getResourceId($ch)] = array('handle' => $ch, 'callback' => $callback);
    }


    /**
     * Returns the resource id of the supplied resource.
     *
     * Takes in a resource, and extracts out the internal resource id that
     * has been allocated by PHP. This acts on the assumption that the value
     * returned when converting to a string will be along the lines of
     * "Resource id #NNNNN".
     *
     * @access private
     * @param resource $resource The resource to find the id for.
     * @return bool
     */
    private function getResourceId ($resource) {
        // If not a resource, then return nothing.
        if (!is_resource($resource))
            return 0;

        // Calculate id.
        $id = (string)$resource;
        $id = substr($id, strpos($id, '#') + 1);
        return (int)$id;
    }


    /**
     * Executes the queue of requests that will need to be executed.
     *
     * Additionally, a $reset parameter that can be passed in, which will clear
     * the queue of requests after they have been processed.
     *
     * If there are no requests in the queue, this method will return a boolean
     * false, otherwise a boolean true will be returned. A return value of true
     * doesn't indicate whether the requests were successful or not - merely
     * that they ran.
     *
     * @access public
     * @param boolean $reset Whether or not to clear the queue after execution.
     * @return boolean
     */
    public function request ($reset=true) {
        if (empty($this->requests))
            return false;

        $max_execution_time = ini_get('max_execution_time');
        ini_set('max_execution_time', 0);

        // Initialise the main handle.
        $mh = curl_multi_init();

        // Add handles.
        foreach ($this->requests as $request)
            curl_multi_add_handle($mh, $request['handle']);

        // Start processes running.
        $running = 0;
        do
        {
            // Continue running.
            $mrc = curl_multi_exec($mh, $running);

            // Run callbacks as soon as the download is finished.
            while (($request = curl_multi_info_read($mh)) !== false)
            {
                // Get callback.
                $callback = $this->requests[$this->getResourceId($request['handle'])]['callback'];

                // If is callable, then call it.
                if (is_callable($callback))
                    call_user_func($callback, curl_multi_getcontent($request['handle']), curl_getinfo($request['handle']));

                // Remove from the multi handle.
                curl_multi_remove_handle($mh, $request['handle']);
            }

            usleep(1000);
        }
        while ($running > 0);

        // Close the main multi handle.
        curl_multi_close($mh);

        // Reset the request array if specified.
        if ($reset)
            $this->requests = array();

        ini_set('max_execution_time', $max_execution_time);

        // The requests ran.
        return true;
    }
}