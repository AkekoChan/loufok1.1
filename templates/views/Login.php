<?php
    namespace App\Templates\Views;

    use App\Components;
use App\Service\Interfaces\Component;
use App\Service\Interfaces\Template;

    class Login extends Template {
        public function render () {
            ?>
            <!DOCTYPE html>
                <html lang="en">
                <head>
                    <?php $this->component(Components\Head::class); ?>
                    <link rel="stylesheet" href="<?php $this->public("/css/login.css"); ?>">
                </head>
                <body>
                    <main>
                        <form action='' method='POST'> 
                            <h1>LOUFOK</h1>
                            <div class="form__input">
                                <input required type='email' id="mail" name='mail' value="<?php echo $this->request["mail"] ?? ''; ?>"/>
                                <label for="mail">Adresse-mail</label>
                            </div>
                            <?php if($this->error === "mail") $this->component(Components\FormError::class, [ "error_text" => "Email incrorrect" ]); ?>
                            
                            <div class="form__input">
                                <input required type='password' id="password" name='password'/>
                                <label for="password">Mot de passe</label>
                            </div>
                            <?php if($this->error === "password") $this->component(Components\FormError::class, [ "error_text" => "Mot de passe incrorrect" ]); ?>
                            
                            <input class="submit__btn" type='submit' value="CONNEXION">
                        </form>
                        <span>conditions d'utilisations</span>
                    </main>
                </body>
            </html>
            <?php
        }
    }
?>