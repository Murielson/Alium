<?php
require_once 'init.php';

if(!isLogged()) {
    redirect('signin.php');
    exit();
}

$user_id = $_SESSION['user']['id'];

$description = $_SESSION['user']['description'] == '' ? "Você ainda não falou nada sobre você? Que tal nos contar um pouco? =)" : $_SESSION['user']['description'];

$query = "SELECT * FROM `users` WHERE `id` = ?";
$stmt = $GLOBALS['pdo']->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$query = "SELECT * FROM `services`";
$stmt = $GLOBALS['pdo']->prepare($query);
$stmt->execute();
$services = $stmt->fetchAll();

$query= "SELECT * FROM `images` WHERE `user_id` = ?";
$stmt= $GLOBALS["pdo"]->prepare($query);
$stmt-> execute([$_SESSION["user"]["id"]]);
$images = $stmt->fetchAll();
$images_ids = [];
if (sizeof($images) > 0) {
    foreach ($images as $image) {
        array_push($images_ids, $image['service_id']);
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/profile.css">
</head>
<body>
    <div class="header">
        <div class="menu-container">
            <a href="index.php" class="menu-logo"><img src="images/icons/logo.svg" alt=""></a>
            <nav class="menu-nav">
                <ul>
                    <?php if(isset($_SESSION['user'])):?>
                        <!-- <li><a>Bem-vindo(a),</a></li> -->
                        <li><a href="profile.php"><?= $_SESSION['logged-user']?></a></li>
                        <li><a href="logout.php" class="text-color-yellow">Sair</a></li>
                    <?php else :?>
                        <li><a href="signup.php">Registrar-se</a></li>
                        <li><a href="signin.php">Entrar</a></li>
                    <?php endif;?>
                </ul>
            </nav>
        </div>
    </div>
    <div class="content-profile">
        <div class="perfil-provider">
            <div class="perfil-img">
                <div class="img">
                    <img src="./images/profile/perfilImage.jpg" alt="">
                </div>
                <br>
                <p><span class="name"><?= $user['name'] ?></span><br><i class="fa fa-map-marker" aria-hidden="true"></i> <?= $user['city'] ?>, <?= $user['state'] ?>, <br>Pintor</p>
            </div>
            <div class="profile-nav">
                <div class="profile-item">
                    <h3>Sobre
                        <a href="#" id="edit-button" onclick="showAboutForm()"><i class="fa fa-pencil fa-1" aria-hidden="true"></i></a>
                        <a href="#" id="hide-about-form" onclick="hideAboutForm()"><i class="fa fa-times fa-1" aria-hidden="true"></i></a>
                    </h3>
                    <p id="about-content"><?= $description ?></p>
                    <form id="about-form" action="update_profile.php" method="POST">
                        <textarea name="about" cols="5" rows="6"><?= $description ?></textarea>
                        <input type="submit" value="Salvar">
                    </form>
                </div>
                <div class="profile-item">
                    <h3>Avaliação</h3>
                    <p>4.5 / 5.0 - Ótimo</p>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star-half-o checked"></span>
                </div>
                <div class="profile-item">
                    <h3>Contatos</h3>
                    <ul>
                        <li><i class="fa fa-whatsapp" aria-hidden="true"></i> <?= $user['phone'] ?></li>
                        <li><i class="fa fa-instagram" aria-hidden="true"></i> @pamisley</li>
                        <li><i class="fa fa-twitter" aria-hidden="true"></i> @pam_painter</li>
                    </ul>
                </div>
            </div>
        </div>
        <section class="profile-edit">
            <div class="profile-form">
                <div class="profile-text-edit">
                    <h3 id="text" style="max-width: 500px;line-height:2em;">Olá, <?= $_SESSION['logged-user'] ?>, seja bem-vindo(a) ao Alium :)<br>Por favor, finalize seu cadastro abaixo.<br><a href="#" class="btn" onclick="showForm(this)">Atualizar Cadastro</a></h3>
                    <h3 id="edit" style="display: none;">Editar Perfil</h3>
                    <form action="update_profile.php" method="POST" id="form">
                        <br>
                        <label for="cpf_cnpj">CPF/CNPJ:</label><br>
                        <input type="text" id="cpf_cnpj" name="cpf_cnpj" onfocus="removeMask(this);" onblur="addCpfCnpjMask(this);" value="<?= $user['cpf_cnpj'] ?>" required><br>
                        <label for="name">Nome:</label><br>
                        <input type="text" id="name" name="name" value="<?= $user['name'] ?>" required><br>
                        <label for="email">E-mail:</label><br>
                        <input type="email" id="email" name="email" value="<?= $user['email'] ?>" required><br>
                        <label for="phone">Telefone:</label><br>
                        <input type="text" id="phone" name="phone" onfocus="removeMask(this);" onblur="addPhoneMask(this);" value="<?= $user['phone'] ?>" required><br>
                        <label for="cep">CEP:</label><br>
                        <input type="text" id="cep" name="cep" value="<?= $user['postal_code'] ?>"  size="10" maxlength="9" onblur="pesquisacep(this.value);" required><br>
                        <label for="address">Endereço:</label><br>
                        <input type="text" id="address" name="address" value="<?= $user['address'] ?>" required><br>
                        <label for="address_number">Número:</label><br>
                        <input type="text" id="address_number" name="address_number" value="<?= $user['address_number'] ?>" required><br>
                        <label for="neighborhood">Bairro:</label><br>
                        <input type="text" id="neighborhood" name="neighborhood" value="<?= $user['neighborhood'] ?>" required><br>
                        <label for="city">Cidade:</label><br>
                        <input type="text" id="city" name="city" value="<?= $user['city'] ?>" required><br>
                        <label for="state">Estado:</label><br>
                        <input type="text" id="state" name="state" value="<?= $user['state'] ?>" required><br>
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>"><br>
                        <!-- <label for="profession">Tipo de Serviço:</label><br> -->
                        <?php if ($user['role'] === 'worker'): ?>
                            <?php foreach ($services as $i => $service): ?>
                                <?php if (sizeof($images_ids) > 0 && in_array($service['id'], $images_ids)): ?>
                                    <input id=<?=$i?> type="checkbox" name="<?= $service['service'] ?>" value="<?=$service['service']?>" checked>
                                <?php else: ?>
                                    <input id=<?=$i?> type="checkbox" name="<?= $service['service'] ?>" value="<?=$service['service']?>">
                                <?php endif ?>
                                <label for=<?=$i?>><?= $service['service'] ?></label><br>
                            <?php endforeach ?>
                        <?php endif ?>
                        <!-- <label for="other">Outro:</label><br>
                        <input type="text" id="other" name="other_service"> -->
                        <br>
                        <label for="portfolio">Portfólio:</label><br>
                        <input type="file" id="portfolio" name="portfolio"><br>
                        <input type="submit" value="Atualizar"><br><br>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <section class="footer">
        <div class="footer-container">
            <div class="logo-footer">
                <img src="images/icons/logo-footer.svg" alt="">
            </div>
        </div>
    </section>
    <script>
        function showForm(e){
            e.style.display = "none";
            document.querySelector('#text').style.display="none";
            document.querySelector('#form').style.display="block";
            document.querySelector('#edit').style.display="block";
        }
        function showAboutForm() {
            document.querySelector('#about-form').style.display = "block";
            document.querySelector('#hide-about-form').style.display = "block";
            document.querySelector('#edit-button').style.display = "none";
            document.querySelector('#about-content').style.display = "none";
        }
        function hideAboutForm() {
            document.querySelector('#about-form').style.display = "none";
            document.querySelector('#hide-about-form').style.display = "none";
            document.querySelector('#edit-button').style.display = "block";
            document.querySelector('#about-content').style.display = "block";
        }
    </script>
</body>
<script src="js/cep.js"></script>
<script src="js/masks.js"></script>
</html>