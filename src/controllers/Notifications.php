<?php
    namespace App\Controllers;

    use App\Service\Routing\Response;
    use App\Service\Interfaces\Controller;
    use App\Service\Managers\Store;
    use App\Service\Plugins\JsonHelper;

    class Notifications extends Controller {
        public function subscribe () : Response {
            $jsonHelper = new JsonHelper("/private/subscribers.json");

            date_default_timezone_set("GMT");

            $rand = rand(15, 18);

            $date = date_create("2023-12-01 14:$rand");

            // Pour la prod
            // $rand = rand(07, 13);

            // $date = date_create("2023-12-$rand 12:50");

            $timestamp = date_timestamp_get($date);

            Store::setCookie("locked", true, $timestamp - time());

            $this->request->post["timestamp"] = $timestamp + 60;

            array_push($jsonHelper->data, $this->request->post);

            return $this->response->content($this->request->post)->status(201);
        }
    }
?>
