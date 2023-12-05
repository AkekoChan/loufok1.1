<?php
    namespace App\Service\Debug;

    use App\Service\Routing\Request;
    use App\Service\Routing\Response;

    class ServiceFatal {
        public function __construct(array|object|string $error)
        {
            $error = $this->get_typed_error($error);
            $request = Request::instance();
            $response = new Response;
            $response->header("x-robots-tag", "noindex")
            ->cache("-disable")->status(500);

            if(ENV->MODE === "PROD") return $response->throw(500);

            if(str_contains($request->accept, "application/json")) {
                $error["error"] = "Internal Server Error";
                return $response->content($error);
            }

            $code = $this->generate_html_code($error["file"], $error["line"]);
            return $response->template(\App\Templates\Errors\Fatal::class, [
                "error" => $error,
                    "code" => $code
                ]);
        }

        private function generate_html_code (string $file, int $line) : string {
            $file_reader = new \SplFileObject($file, 'r');

            $lines_num = "";
            $code_lines = "";

            for ($i = -10; $i < 10; $i++) { 
                if($line + $i < 0) {
                    continue;
                }

                if($file_reader->eof()) break;
                
                $line + $i === 0 ? $file_reader->fseek(0) : $file_reader->seek($line + $i - 1);
                $lines_num .= "<i>".($line + $i)."</i>";

                if($i == 0) {
                    $code_lines .= "<span data-highlight='1'>".htmlspecialchars($file_reader->current())."</span>";
                } else {
                    $code_lines .= "<span>".htmlspecialchars($file_reader->current())."</span>";
                }
            }

            return "<code class='code'>
                <div class='lines'>$lines_num</div>
                <pre class='code_lines'>$code_lines</pre>
            </code>";
        }

        private function get_typed_error (array|object|string $error) : array {
            if(is_object($error)) {
                return [
                    "message" => $error->getMessage(),
                    "file" => $error->getFile(),
                    "line" => $error->getLine(),
                    "trace" => $error->getTrace()
                ];
            } else if(is_array($error)) {
                return [
                    "message" => $error[1],
                    "file" => $error[2],
                    "line" => $error[3],
                    "trace" => $error[4] ?? []
                ];
            } else {
                $trace = debug_backtrace();
                array_shift($trace); // ? Remove this function from the trace
                $last_trace = array_shift($trace);
                return [
                    "message" => $error,
                    "file" => $last_trace["file"],
                    "line" => $last_trace["line"],
                    "trace" => $trace
                ];
            }
        }
    }
?>