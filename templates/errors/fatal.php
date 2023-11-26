<?php
    namespace App\Templates\Errors;
    use App\Service\Interfaces\Template;
    
    class Fatal extends Template {
        public function render ()
        {
            ?>
                <!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
                    <title>
                        Fatal Error: <?php echo substr(strrchr($this->error["file"], "\\"), 1) ?> (Line <?php echo $this->error["line"] ?>)
                    </title>
                </head>
                <body>
                    <style>
                        :root {
                            --error-red: #d44444;
                            --error-red-100: #752323;
                            --default-light: #F7F7F7;
                            --default-dark: #191919;
                            --default-grey: #9c9c9c;
                        }

                        .container {
                            width: 100%;
                            display: flex;
                            align-items: center;
                        }

                        .container.column {
                            justify-content: initial;
                            align-items: initial;
                            flex-direction: column;
                        }

                        html, body {
                            line-height: 1.6 !important;
                            margin: 0;
                            font-size: 16px;
                            min-height: 100%;
                            background-color: var(--default-light);
                            color: var(--default-dark);
                            font-family: Arial, sans-serif;
                        }

                        * {
                            box-sizing: border-box;
                            outline: none;
                            list-style: none;
                            border: none;
                            background: none;
                            font-style: normal;
                            text-decoration: none;
                            color: unset;
                            margin: 0;
                            padding: 0;
                        }

                        main {
                            width: 100%;
                            padding: 0 !important;
                            background-color: #191919;
                        }

                        .top__hat {
                            width: 100%;
                            background-color: var(----default-dark);
                            color: var(--default-light);
                            padding: 1rem 10vw;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                        }

                        .top__title, .documentation {
                            display: flex;
                            align-items: center;
                            gap: .6rem;
                            font-size: 1.2rem;
                        }

                        .main {
                            width: 100%;
                        }

                        .main__message {
                            width: 100%;
                            background-color: var(--error-red);
                            padding: 1rem 10vw;
                            color: var(--default-light);
                            font-size: 1.2rem;
                        }

                        .main__spec {
                            width: 100%;
                            background-color: var(--error-red-100);
                            padding: .6rem 10vw;
                            font-size: 1rem;
                            color: var(--default-light);
                        }

                        .stacks {
                            padding: 2rem 10vw;
                            background-color: #dedede;
                            color: #191919;
                        }

                        .stacks h1 {
                            font-size: 1.4rem;
                        }

                        .code {
                            display: flex;
                            align-items: center;
                            padding: 1rem 10vw;
                            background-color: var(--default-dark);
                            color: var(--default-light);
                        }

                        .code pre {
                            width: 100%;
                            overflow-x: scroll;
                            text-overflow: ellipsis;
                            padding: 1rem 0;
                        }

                        .code .lines {
                            height: 100%;
                            display: flex;
                            flex-direction: column;
                            justify-content: space-around;
                            align-items: flex-end;
                            margin: 0 1rem;
                            padding: 0 1rem;
                            border-right: 1.5px solid #595959;
                        }

                        .code .lines i {
                            display: block;
                            width: fit-content;
                        }

                        .code span[data-highlight] {
                            background-color: #e05252ec;
                            color: var(--default-dark);
                        }

                        .code span {
                            color: var(--default-grey);
                            padding: .2rem 0;
                        }
                    </style>
                    <main>
                        <div class="top__hat">
                            <div class="top__title"><i class="icon"></i><span>Mosaic</span></div>
                            <div class="documentation"><i class="icon"></i><a href="">Documentation</a></div>
                        </div>
                        <div class="main">
                            <div class="main__message">
                                <?php echo $this->error["message"] ?> : Fatal Error
                            </div>
                            <div class="main__spec">
                                <?php echo $this->error["file"] ?> (Line <?php echo $this->error["line"] ?>)
                            </div>
                            <?php echo $this->code ?>
                            <div class="stacks">
                                <h1>Stack trace</h1>
                                <?php foreach($this->error["trace"] as $stack) {
                                    ?>
                                        <div class="stack__item">
                                            <?php echo $stack["file"] ?> (Line <?php echo $stack["line"] ?>)
                                        </div>
                                    <?php
                                } ?>
                            </div>
                        </div>
                    </main>
                </body>
                </html>
            <?php
        }
    }
?>