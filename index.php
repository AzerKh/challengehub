<?php
session_start();

require_once "app/models/User.php";
require_once "app/models/Challenge.php";

$action = $_GET['action'] ?? null;

/* ================= REGISTER ================= */
if ($action == "register" && $_SERVER["REQUEST_METHOD"] == "POST") {

    $user = new User();
    $result = $user->register($_POST['name'], $_POST['email'], $_POST['password']);

    if ($result === "email_exists") {
        echo "❌ Email déjà utilisé";
    } elseif ($result === true) {
        echo "✅ Inscription réussie";
    } else {
        echo "❌ Erreur serveur";
    }
    exit;
}

/* ================= LOGIN ================= */
if ($action == "login" && $_SERVER["REQUEST_METHOD"] == "POST") {

    $user = new User();
    $result = $user->login($_POST['email'], $_POST['password']);

    if ($result) {
        $_SESSION['user'] = $result;
        header("Location: index.php?action=dashboard");
        exit;
    } else {
        echo "❌ Email ou mot de passe incorrect";
        exit;
    }
}

if ($action == "login") {
    include "app/views/login.php";
    exit;
}

/* ================= DASHBOARD ================= */
if ($action == "dashboard") {

    if (!isset($_SESSION['user'])) {
        header("Location: index.php?action=login");
        exit;
    }

    include "app/views/dashboard.php";
    exit;
}

/* ================= CREATE CHALLENGE PAGE ================= */
if ($action == "create_challenge") {

    if (!isset($_SESSION['user'])) {
        header("Location: index.php?action=login");
        exit;
    }

    include "app/views/challenges/create_challenge.php";
    exit;
}

/* ================= STORE CHALLENGE ================= */
if ($action == "store_challenge" && $_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_SESSION['user'])) {
        header("Location: index.php?action=login");
        exit;
    }

    $challenge = new Challenge();

    $result = $challenge->create(
        $_SESSION['user']['id'],
        $_POST['title'],
        $_POST['description'],
        $_POST['category'],
        $_POST['deadline'],
        $_POST['image'] ?? null
    );

    header("Location: index.php?action=list_challenges");
    exit;
}

/* ================= LIST CHALLENGES ================= */
if ($action == "list_challenges") {

    $challengeModel = new Challenge();
    $challenges = $challengeModel->getAll();

    include "app/views/challenges/list.php";
    exit;
}

/* ================= EDIT CHALLENGE ================= */
if ($action == "edit_challenge") {

    if (!isset($_SESSION['user'])) {
        header("Location: index.php?action=login");
        exit;
    }

    if (!isset($_GET['id'])) {
        die("ID manquant");
    }

    $challengeModel = new Challenge();
    $challenge = $challengeModel->getById($_GET['id']);

    if (!$challenge || $challenge['user_id'] != $_SESSION['user']['id']) {
        die("Accès refusé");
    }

    include "app/views/challenges/edit.php";
    exit;
}

/* ================= UPDATE CHALLENGE ================= */
if ($action == "update_challenge" && $_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_SESSION['user'])) {
        header("Location: index.php?action=login");
        exit;
    }

    $challengeModel = new Challenge();

    $challengeModel->update(
        $_POST['id'],
        $_SESSION['user']['id'],
        $_POST['title'],
        $_POST['description'],
        $_POST['category'],
        $_POST['deadline'],
        $_POST['image']
    );

    header("Location: index.php?action=list_challenges");
    exit;
}

/* ================= DELETE CHALLENGE ================= */
if ($action == "delete_challenge") {

    if (!isset($_SESSION['user'])) {
        header("Location: index.php?action=login");
        exit;
    }

    if (!isset($_GET['id'])) {
        die("ID manquant");
    }

    $challengeModel = new Challenge();
    $challengeModel->delete($_GET['id'], $_SESSION['user']['id']);

    header("Location: index.php?action=list_challenges");
    exit;
}

/* ================= EDIT PROFILE ================= */
if ($action == "edit_profile") {

    if (!isset($_SESSION['user'])) {
        header("Location: index.php?action=login");
        exit;
    }

    include "app/views/edit_profile.php";
    exit;
}

/* ================= UPDATE PROFILE ================= */
if ($action == "update_profile" && $_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_SESSION['user'])) {
        header("Location: index.php?action=login");
        exit;
    }

    $user = new User();
    $user->updateProfile(
        $_SESSION['user']['id'],
        $_POST['name'],
        $_POST['email']
    );

    $_SESSION['user']['name']  = $_POST['name'];
    $_SESSION['user']['email'] = $_POST['email'];

    header("Location: index.php?action=edit_profile&profile_success=1");
    exit;
}

/* ================= CHANGE PASSWORD ================= */
if ($action == "change_password" && $_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_SESSION['user'])) {
        header("Location: index.php?action=login");
        exit;
    }

    $user = new User();

    $result = $user->changePassword(
        $_SESSION['user']['id'],
        $_POST['old_password'],
        $_POST['new_password']
    );

    if ($result === true) {
        header("Location: index.php?action=edit_profile&pwd_success=1");
    } else {
        header("Location: index.php?action=edit_profile&pwd_error=1");
    }
    exit;
}

/* ================= LOGOUT ================= */
if ($action == "logout") {
    session_destroy();
    header("Location: index.php?action=login");
    exit;
}

/* ================= DEFAULT ================= */
include "app/views/register.php";
exit;