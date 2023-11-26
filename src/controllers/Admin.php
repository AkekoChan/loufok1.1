<?php
    namespace App\Controllers;

    use App\Helpers\Auth;
    use App\Service\Routing\Response;
    use App\Service\Interfaces\Controller;

    use App\Templates\Views;
    use App\Models;

    class Admin extends Controller {
        public function index () : Response {
            $user = Auth::fromCookie();
            if($user === false) return $this->response->redirect("/login");
            if($user->is_admin === false) return $this->response->throw(404);

            if(!empty($this->request->post)) {
                // GERER CREATION CADAVRE (RECOUVREMENT PERIODE)
                $gpost = fn(string $key) => isset($this->request->post[$key]) ? $this->request->post[$key] : $this->response->redirect("/create?error=2004&field=$key");
                $contributions_count = $gpost('contributions-count');
                $title = $gpost("cadaver-title");
                $contribution_text =$gpost('contribution'); 
                $date_start = $gpost('date-start');
                $date_end = $gpost('date-end');
                $contributions_count++; // FIX SOME TWIKS (￣y▽,￣)╭ 
                if($contributions_count < 1) return $this->response->redirect("/create?error=3001");

                $textlen = mb_strlen(str_replace(["\n", "\r", "\t"], "", $contribution_text ?? ""));     
                if($textlen < 50 || $textlen > 280) return $this->response->redirect("/create?error=3002");

                if($date_start >= $date_end) return $this->response->redirect("/create?error=3003");
                if($date_start < date("Y-m-d")) return $this->response->redirect("/create?error=3008");
                if(Models\CadavreExquisModel::instance()->periodeOverlap($date_start, $date_end)) return $this->response->redirect("/create?error=3004");

                try {
                    $created = Models\CadavreExquisModel::instance()->new($title, $date_start, $date_end,
                        $contribution_text, $contributions_count, $user->id);
                } catch (\Throwable $th) {
                    // title is the same as other one
                    return $this->response->redirect("/create?error={$th->getCode()}");
                }

                if(!$created) return $this->response->redirect("/create?error=3005");

                return $this->response->redirect("/?success=" . urlencode($title));
            }   

            return $this->response->template(Views\Admin\Create::class, [
                "user" => $user,
                "error" => $this->request->get["error"] ?? null,
                "title" => "Loufok | Créer votre Cadavre Exquis"
            ]);
        }
    }
?>