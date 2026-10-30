<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/config/funcoes.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/modais.php");
isLogged($sid);

if ($acao == "servidor") :

    if (isset($_POST['excluir_servidor'])) :

        $id = $_POST['id'];
        $id_owner = $_POST['id_owner'];

        $sql = $conn->query("DELETE FROM servidores WHERE id='$id' AND id_owner='$id_owner'");

        if ($sql) :
            echo "<script>
    Swal.fire({icon: 'success', title: 'Servidor excluido com sucesso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
    window.location='" . getConfig('link') . "/app.php';
    </script>";
        else :
            echo "<script>
        Swal.fire({icon: 'error', title: 'Falha ao excluir servidor !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
        window.location='" . getConfig('link') . "/app.php';
        </script>";
        endif;
    else :
        header("location: /");
    endif;

elseif ($acao == "payload") :

    if (isset($_POST['excluir_payload'])) :

        $id = $_POST['id'];
        $id_owner = $_POST['id_owner'];

        $sql = $conn->query("DELETE FROM payloads WHERE id='$id' AND id_owner='$id_owner'");

        if ($sql) :
            echo "<script>
            Swal.fire({icon: 'success', title: 'Payload excluida com sucesso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
            window.location='" . getConfig('link') . "/app.php';
            </script>";
        else :
            echo "<script>
                Swal.fire({icon: 'error', title: 'Falha ao excluir Payload !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
                window.location='" . getConfig('link') . "/app.php';
                </script>";
        endif;
    else :
        header("location: /");
    endif;

elseif ($acao == "porta") :

    if (isset($_POST['excluir_porta'])) :

        $id = $_POST['id'];
        $id_owner = $_POST['id_owner'];

        $sql = $conn->query("DELETE FROM portas WHERE id='$id' AND id_owner='$id_owner'");

        if ($sql) :
            echo "<script>
                Swal.fire({icon: 'success', title: 'Porta excluida com sucesso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
                window.location='" . getConfig('link') . "/app.php';
                </script>";
        else :
            echo "<script>
                    Swal.fire({icon: 'error', title: 'Falha ao excluir Porta !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
                    window.location='" . getConfig('link') . "/app.php';
                    </script>";
        endif;
    else :
        header("location: /");
    endif;
elseif ($acao == "usuario") :

    if (getOwner($uid) == false) :
        header("location: /");
    endif;

    if (isset($_GET['id'])) :

        $id = $_GET['id'];
        $pasta = getData('pasta_att', $id);

        $sql = $conn->query("SELECT nivel FROM usuarios WHERE id='$id'")->fetch();

        if ($sql[0] >= 3) :
            echo "<script>
            Swal.fire({icon: 'error', title: 'Você não pode excluir o dono do site !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
            window.location='" . getConfig('link') . "/adicionar.php';
            </script>";
        else :

            $conn->query("DELETE FROM usuarios WHERE id='$id'");
            $conn->query("DELETE FROM configuracoes WHERE id_owner='$id'");
            $conn->query("DELETE FROM servidores WHERE id_owner='$id'");
            $conn->query("DELETE FROM payloads WHERE id_owner='$id'");
            $conn->query("DELETE FROM portas WHERE id_owner='$id'");
            $sql = $conn->query("DELETE FROM mensagens WHERE id_owner='$id'");

            delTree("update/$pasta");

            if ($sql) :
                echo "<script>
                    Swal.fire({icon: 'success', title: 'Usuário excluido com sucesso !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
                    window.location='" . getConfig('link') . "/adicionar.php';
                    </script>";
            else :
                echo "<script>
                        Swal.fire({icon: 'error', title: 'Falha ao excluir usuário !', confirmButtonText: 'Entendi', confirmButtonColor: '#16a34a'});
                        window.location='" . getConfig('link') . "/adicionar.php';
                        </script>";
            endif;
        endif;
    else :
        header("location: /");
    endif;
endif;
