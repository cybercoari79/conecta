<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/config/funcoes.php");
isLogged($sid);

if ($acao == "servidor") :

    if (isset($_POST['atualiza_servidor'])) :

        $id_owner = $_POST['id_owner'];
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $tipo = $_POST['tipo'];
        $flag = $_POST['flag'];
        $serverip = $_POST['serverip'];
        $checkuser = $_POST['checkuser'];
        $serverport = $_POST['serverport'];
        $sslport = $_POST['sslport'];

        if (empty($nome)) :
            echo "<script>
    Swal.fire({icon: 'error', title: 'Nome não pode ficar vazio !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
    window.location='" . getConfig('link') . "/app.php';
    </script>";

        else :

            $sql = $conn->prepare("UPDATE servidores SET id_owner='$id_owner', Name='$nome', TYPE='$tipo', FLAG='$flag', ServerIP='$serverip', CheckUser='$checkuser', ServerPort='$serverport', SSLPort='$sslport', USER='', PASS='' WHERE id='$id'");
            $sql->execute();

            echo "<script>
        Swal.fire({icon: 'success', title: 'Servidor editado com sucesso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
        window.location='" . getConfig('link') . "/app.php';
        </script>";

        endif;
    else :
        header("location: /");
    endif;

elseif ($acao == "payload") :

    if (isset($_POST['atualiza_payload'])) :
        $id_owner = $_POST['id_owner'];
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $flag = $_POST['flag'];
        $payload = $_POST['pay'];
        $sni = $_POST['sni'];
        $tlsip = $_POST['tlsip'];
        $proxyip = $_POST['proxyip'];
        $proxyport = $_POST['proxyport'];
        $info = $_POST['info'];

        if (empty($nome)) :
            echo "<script>
            Swal.fire({icon: 'error', title: 'Nome não pode ficar vazio !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
            window.location='" . getConfig('link') . "/app.php';
            </script>";

        else :

            $sql = $conn->prepare("UPDATE payloads SET id_owner='$id_owner', Name='$nome', FLAG='$flag', Payload='$payload', SNI='$sni', TlsIP='$tlsip', ProxyIP='$proxyip', ProxyPort='$proxyport', Info='$info' WHERE id='$id'");
            $sql->execute();

            echo "<script>
            Swal.fire({icon: 'success', title: 'Payload editada com sucesso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
            window.location='" . getConfig('link') . "/app.php';
            </script>";

        endif;
    else :
        header("location: /");
    endif;

elseif ($acao == "porta") :

    if (isset($_POST['atualiza_porta'])) :
        $id_owner = $_POST['id_owner'];
        $id = $_POST['id'];
        $porta = $_POST['porta'];

        if (empty($porta)) :
            echo "<script>
                Swal.fire({icon: 'error', title: 'Porta não pode ficar vazio', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
                window.location='" . getConfig('link') . "/app.php';
                </script>";

        else :

            $sql = $conn->prepare("UPDATE portas SET id_owner='$id_owner', Porta='$porta' WHERE id='$id'");
            $sql->execute();

            echo "<script>
                Swal.fire({icon: 'success', title: 'Porta editada com sucesso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
                window.location='" . getConfig('link') . "/app.php';
                </script>";

        endif;
    else :
        header("location: /");
    endif;

elseif ($acao == "sms") :

    if (isset($_POST['atualiza_sms'])) :

        $id_owner = $_POST['id_owner'];
        $sms = $_POST['sms'];

        $busca = $conn->query("SELECT * FROM mensagens WHERE id_owner='$id_owner'");

        if ($busca->rowCount() > 0) :

            $sql = $conn->query("UPDATE mensagens SET msg='$sms' WHERE id_owner='$id_owner'");

        else :

            $sql = $conn->query("INSERT INTO mensagens SET id_owner='$id_owner', msg='$sms'");

        endif;


        addSms($id_owner);

        $busca = $conn->query("SELECT * FROM mensagens WHERE id_owner='$id_owner'")->fetch();

        $pronto = '{"SendMessage":"' . $busca['att'] . '","MyMessage":"' . $busca['msg'] . '"}';

        $path = "update/" . getData('pasta_att', $uid) . "";
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $config = "update/" . getData('pasta_att', $uid) . "/sms";

        //verifica se já tem
        if (file_exists($config)) {
            unlink($config);
        }

        file_put_contents($config, $pronto);

        echo "<script>
        Swal.fire({icon: 'success', title: 'SMS enviado com sucesso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
        window.location='" . getConfig('link') . "/app.php';
        </script>";
    else :
        header("location: /");
    endif;

elseif ($acao == "config") :

    if (isset($_POST['atualiza_config'])) :

        $id_owner = $_POST['id_owner'];
        $notas = $_POST['notas'];
        $logo_apk = trim($_POST['logo_apk'] ?? '');
        $fundo_apk = trim($_POST['fundo_apk'] ?? '');
        $sms = getConfig('link') . '/update/' . getData('pasta_att', $uid) . '/sms';
        $update = getConfig('link') . '/update/' . getData('pasta_att', $uid) . '/config';
        $email = $_POST['email'];
        $contato = $_POST['contato'];
        $termos = $_POST['termos'];
        $checkuser = $_POST['checkuser'];

        $sql = $conn->query("SELECT * FROM configuracoes WHERE id_owner='$id_owner'");

        if ($sql->rowCount() == 0) :
            $config = $conn->query("INSERT INTO configuracoes SET id_owner='$id_owner', notas='$notas', sms='$sms', att='$update', email='$email', contato='$contato', termos='$termos', checkuser='$checkuser', logo_apk='$logo_apk', fundo_apk='$fundo_apk'");

            if ($config) :
                echo "<script>
            Swal.fire({icon: 'success', title: 'Configurações editadas com sucesso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
            window.location='" . getConfig('link') . "/app.php';
            </script>";
            endif;
        elseif ($sql->rowCount() == 1) :
            $config = $conn->query("UPDATE configuracoes SET notas='$notas', email='$email', contato='$contato', termos='$termos', checkuser='$checkuser', logo_apk='$logo_apk', fundo_apk='$fundo_apk' WHERE id_owner='$id_owner'");

            if ($config) :
                echo "<script>
            Swal.fire({icon: 'success', title: 'Configurações editadas com sucesso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
            window.location='" . getConfig('link') . "/app.php';
            </script>";
            endif;
        endif;
    else :
        header("location: /");
    endif;

elseif ($acao == "site") :

    if (getAdm($uid) == false) :
        header("location: /");
    endif;

    if (isset($_POST['atualiza_site'])) :

        $logo = $_POST['logo'];
        $altura = $_POST['altura'];
        $largura = $_POST['largura'];
        $link = $_POST['link'];
        $titulo = $_POST['titulo'];

        $sql_logo = $conn->query("UPDATE configs SET valor='$logo' WHERE nome='logo'");
        $sql_altura = $conn->query("UPDATE configs SET valor='$altura' WHERE nome='altura'");
        $sql_largura = $conn->query("UPDATE configs SET valor='$largura' WHERE nome='largura'");
        $sql_link = $conn->query("UPDATE configs SET valor='$link' WHERE nome='link'");
        $sql_titulo = $conn->query("UPDATE configs SET valor='$titulo' WHERE nome='titulo'");

        if ($sql_titulo) :
            echo "<script>
        Swal.fire({icon: 'success', title: 'Configurações do site editadas com sucesso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
        window.location='" . getConfig('link') . "';
        </script>";
        endif;
    else :
        header("location: /");
    endif;

elseif ($acao == "dados") :

    if ($_POST['nome']) :
        $nome = $_POST['nome'];
        $sql = $conn->query("SELECT * FROM usuarios WHERE nome='$nome'")->rowCount();

        if ($sql > 0) :
            echo "<script>
                Swal.fire({icon: 'warning', title: 'Este nome de usuário já está em uso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
                window.location='" . getConfig('link') . "/perfil.php';
                </script>";
        elseif (empty($nome)) :
            echo "<script>
        Swal.fire({icon: 'error', title: 'O nome não pode ficar vazio !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
        window.location='" . getConfig('link') . "/perfil.php';
        </script>";
        else :
            $sql = $conn->query("UPDATE usuarios SET nome='$nome' WHERE id='" . getIdBySid($sid) . "'");
            echo "<script>
                Swal.fire({icon: 'success', title: 'Nome atualizado com sucesso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
                window.location='" . getConfig('link') . "/perfil.php';
                </script>";
        endif;

    elseif ($_POST['login']) :
        $login = $_POST['login'];
        $sql = $conn->query("SELECT * FROM usuarios WHERE login='$login'")->rowCount();

        if ($sql > 0) :
            echo "<script>
                Swal.fire({icon: 'warning', title: 'Este login de usuário já está em uso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
                window.location='" . getConfig('link') . "/perfil.php';
                </script>";
        elseif (empty($login)) :
            echo "<script>
        Swal.fire({icon: 'error', title: 'O login não pode ficar vazio !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
        window.location='" . getConfig('link') . "/perfil.php';
        </script>";
        else :
            $sql = $conn->query("UPDATE usuarios SET login='$login' WHERE id='" . getIdBySid($sid) . "'");
            echo "<script>
                Swal.fire({icon: 'success', title: 'Login atualizado com sucesso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
                window.location='" . getConfig('link') . "/perfil.php';
                </script>";
        endif;
    elseif ($_POST['senha']) :
        $senha = $_POST['senha'];
        $senha = md5($senha);

        if (empty($senha)) :
            echo "<script>
        Swal.fire({icon: 'error', title: 'A senha não pode ficar em branco !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
        window.location='" . getConfig('link') . "/perfil.php';
        </script>";
        else :

            $sql = $conn->query("UPDATE usuarios SET senha='$senha' WHERE id='" . getIdBySid($sid) . "'");
            echo "<script>
                    Swal.fire({icon: 'success', title: 'Senha atualizada com sucesso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
                    window.location='" . getConfig('link') . "/perfil.php';
                    </script>";
        endif;

    elseif ($_POST['pasta']) :
        $pasta = $_POST['pasta'];

        $sql = $conn->query("SELECT * FROM usuarios WHERE pasta_att='$pasta'");
        $contar = $sql->rowCount();
        $dados = $sql->fetch(PDO::FETCH_ASSOC);
        if ($contar > 0 && $dados['id'] != $uid) :
            echo "<script>
        Swal.fire({icon: 'warning', title: 'Esta pasta já está em uso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
        window.location='" . getConfig('link') . "/perfil.php';
        </script>";
        elseif (empty($pasta)) :
            echo "<script>
        Swal.fire({icon: 'error', title: 'O nome da pasta não pode ficar vazio !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
        window.location='" . getConfig('link') . "/perfil.php';
        </script>";
        else :
            $pasta_velha = "update/" . $_POST['pasta_velha'];
            $pasta_nova = "update/" . $_POST['pasta'];

            rename($pasta_velha, $pasta_nova);

            $sms = getConfig('link') . '/update/' . $_POST['pasta'] . '/sms';
            $update = getConfig('link') . '/update/' . $_POST['pasta'] . '/config';

            $conn->query("UPDATE configuracoes SET att='$update', sms='$sms' WHERE id_owner='" . getIdBySid($sid) . "'");

            $sql = $conn->query("UPDATE usuarios SET pasta_att='$pasta' WHERE id='" . getIdBySid($sid) . "'");
            echo "<script>
        Swal.fire({icon: 'success', title: 'Pasta atualizada com sucesso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
        window.location='" . getConfig('link') . "/perfil.php';
        </script>";
        endif;
    endif;

elseif ($acao == "usuarios") :

    if (getAdm($uid) == false) :
        header("location: /");
    endif;

    if (isset($_POST['atualiza_usuarios'])) :

        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $login = $_POST['login'];
        $senha = $_POST['senha'];
        $nivel = $_POST['nivel'];

        if (empty($senha)) :

            $sql = $conn->query("UPDATE usuarios SET nome='$nome', login='$login', nivel='$nivel', pasta_att='" . $_POST['pasta_nova'] . "' WHERE id='$id'");

            $pasta_velha = "update/" . $_POST['pasta_velha'];
            $pasta_nova = "update/" . $_POST['pasta_nova'];

            rename($pasta_velha, $pasta_nova);

            $sms = getConfig('link') . '/update/' . getData('pasta_att', $id) . '/sms';
            $update = getConfig('link') . '/update/' . getData('pasta_att', $id) . '/config';

            $conn->query("UPDATE configuracoes SET att='$update', sms='$sms' WHERE id_owner='$id");

            if ($sql) :
                echo "<script>
                Swal.fire({icon: 'success', title: 'Usuário editado com sucesso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
				window.location='" . getConfig('link') . "/usuarios.php';
                </script>";
            endif;
        else :

            $senha = md5($senha);

            $sql = $conn->query("UPDATE usuarios SET nome='$nome', login='$login', senha='$senha', nivel='$nivel', pasta_att='" . $_POST['pasta_nova'] . "' WHERE id='$id'");

            $pasta_velha = "update/" . $_POST['pasta_velha'];
            $pasta_nova = "update/" . $_POST['pasta_nova'];

            rename($pasta_velha, $pasta_nova);

            $sms = getConfig('link') . '/update/' . getData('pasta_att', $id) . '/sms';
            $update = getConfig('link') . '/update/' . getData('pasta_att', $id) . '/config';

            $conn->query("UPDATE configuracoes SET att='$update', sms='$sms' WHERE id_owner='$id'");

            if ($sql) :
                echo "<script>
                Swal.fire({icon: 'success', title: 'Senha do usuário atualizada com sucesso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
                window.location='" . getConfig('link') . "/usuarios.php';
                </script>";

            endif;
        endif;
    endif;

else :
    header("location: /");
endif;
