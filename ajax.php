<?php

use Iprbooks\Ebs\Sdk\Client;
use Iprbooks\Ebs\Sdk\collections\UsersCollection;
use Iprbooks\Ebs\Sdk\Managers\UserManager;

define('AJAX_SCRIPT', true);
require_once('../../config.php');
require_once($CFG->dirroot . '/blocks/iprbooksmanage/vendor/autoload.php');

require_login();
$action = optional_param('action', "", PARAM_TEXT);
$type = optional_param('type', "", PARAM_TEXT);
$user_id = optional_param('user_id', "", PARAM_TEXT);
$page = optional_param('page', 0, PARAM_INT);

$email = optional_param('email', "", PARAM_TEXT);
$fio = optional_param('fio', "", PARAM_TEXT);
$pass = optional_param('pass', "", PARAM_TEXT);
$user_type = optional_param('user_type', "", PARAM_TEXT);

//user filter
$filter_book = array(
    'filter-user-email' => optional_param('filter-user-email', "", PARAM_TEXT),
    'filter-user-login' => optional_param('filter-user-login', "", PARAM_TEXT),
    'filter-user-fio' => optional_param('filter-user-fio', "", PARAM_TEXT),
);

$user_types = array(
    1 => "Студент",
    2 => "Аспирант",
    3 => "Преподаватель",
    4 => "Не определен",
);

$clientId = get_config('iprbooksmanage', 'user_id');
$token = get_config('iprbooksmanage', 'user_token');

//$clientId = 187;
//$token = '5G[Usd=6]~F!b+L<a4I)Ya9S}Pb{McGX';

$content = "";
try {
    $client = new Client($clientId, $token);
} catch (Exception $e) {
    die();
}

$userManager = new UserManager($client);
switch ($action) {
    case 'getlist':
        $userCollection = new UsersCollection($client);

        //set filters
        $userCollection->setFilter(UsersCollection::EMAIL, $filter_book['filter-user-email']);
        $userCollection->setFilter(UsersCollection::USERNAME, $filter_book['filter-user-login']);
        $userCollection->setFilter(UsersCollection::FULLNAME, $filter_book['filter-user-fio']);
        $userCollection->setOffset($userCollection->getLimit() * $page);
        $userCollection->get();

        foreach ($userCollection as $user) {
            $user->get($user->getId());
            $blocked = $user->getBlocked() == 0 ? "Нет" : "Да";
            $userType = $user_types[$user->getUserType()];
            if ($user->getBlocked() != 0) {
                $button = "<a style=\"\" class=\"btn btn-secondary ipr-user-unblock\" data-id=\"" . $user->getId() . "\" href=\"#unblock\">Восстановить</a>";
            } else {
                $button = "<a style=\"\" class=\"btn btn-secondary ipr-user-block\" data-id=\"" . $user->getId() . "\" href=\"#block\">Заблокировать</a>";
            }
            $content .= "<div class=\"ipr-user-item\" data-id=\"" . $user->getId() . "\">
                            <div class=\"\" style='padding: 10px 10px'>
                                <div class=\"\">
                                    <div id='ipr-user-id-" . $user->getId() . "'><strong>ID:</strong> " . $user->getId() . " </div>
                                    <div id='ipr-user-username-" . $user->getId() . "'><strong>Логин:</strong> " . $user->getUsername() . " </div>
                                    <div id='ipr-user-fullname-" . $user->getId() . "'><strong>ФИО:</strong> " . $user->getFullname() . " </div>
                                    <div id='ipr-user-email-" . $user->getId() . "'><strong>Email:</strong> " . $user->getEmail() . " </div>
                                    <div id='ipr-user-blocked-" . $user->getId() . "'><strong>Заблокирован:</strong> " . $blocked . " </div>
                                    <div id='ipr-user-user_type-" . $user->getId() . "'><strong>Тип пользователя:</strong> " . $userType . " </div>
                                    <div id='ipr-user-class-" . $user->getId() . "'><strong>Класс:</strong> " . $user->getClass() . " </div>
                                    <div id='ipr-user-specialty-" . $user->getId() . "'><strong>Специальность:</strong> " . $user->getSpecialty() . " </div>
                                    <div id='ipr-user-group-" . $user->getId() . "'><strong>Группа:</strong> " . $user->getGroup() . " </div>
                                    <div id='ipr-user-facultet-" . $user->getId() . "'><strong>Факультет:</strong> " . $user->getFacultet() . " </div>
                                    <div id='ipr-user-department-" . $user->getId() . "'><strong>Отдел:</strong> " . $user->getDepartment() . " </div>
                                    <div id='ipr-user-registration_date-" . $user->getId() . "'><strong>Дата регистрации:</strong> " . $user->getRegistrationDate() . "</div>
                                    <div id='ipr-user-blockedafter-" . $user->getId() . "'><strong>Дата окончания подписки:</strong> " . $user->getBlockedAfter() . " </div>
                                </div>
                                <div class=\"\"> " . $button . "</div>
                            </div>
                        </div>";
        }

        $content .= pagination($userCollection->getTotalCount(), $page + 1);
        break;

    case 'block_user':
        $userManager->deleteUser($user_id);
        break;

    case 'unblock_user':
        $userManager->restoreUser($user_id);
        break;
    case 'register_user':
        $user = $userManager->registerNewUser($email, $fio, $pass, $user_type);
        $text = $user->getMessage();
        if ($text == '') {
            $text = "Пользователь успещно зарегистрирован";
        }
        break;
}

echo json_encode(['action' => $action, 'type' => $type, 'html' => $content, 'text' => $text]);

function pagination($count, $page)
{
    $output = '';
    $output .= "<nav aria-label=\"Страница\" class=\"pagination pagination-centered justify-content-center\"><ul class=\"mt-1 pagination \">";
    $pages = ceil($count / 10);


    if ($pages > 1) {

        if ($page > 1) {
            $output .= "<li class=\"page-item\"><a data-page=\"" . ($page - 2) . "\" class=\"page-link iprmanage-page\" ><span>«</span></a></li>";
        }
        if (($page - 3) > 0) {
            $output .= "<li class=\"page-item \"><a data-page=\"0\" class=\"page-link iprmanage-page\">1</a></li>";
        }
        if (($page - 3) > 1) {
            $output .= "<li class=\"page-item disabled\"><span class=\"page-link iprmanage-page\">...</span></li>";
        }


        for ($i = ($page - 2); $i <= ($page + 2); $i++) {
            if ($i < 1) continue;
            if ($i > $pages) break;
            if ($page == $i)
                $output .= "<li class=\"page - item active\"><a data-page=\"" . ($i - 1) . "\" class=\"page-link iprmanage-page\" >" . $i . "</a ></li > ";
            else
                $output .= "<li class=\"page-item \"><a data-page=\"" . ($i - 1) . "\" class=\"page-link iprmanage-page\">" . $i . "</a></li>";
        }


        if (($pages - ($page + 2)) > 1) {
            $output .= "<li class=\"page-item disabled\"><span class=\"page-link iprmanage-page\">...</span></li>";
        }
        if (($pages - ($page + 2)) > 0) {
            if ($page == $pages)
                $output .= "<li class=\"page - item active\"><a data-page=\"" . ($pages - 1) . "\" class=\"page-link iprmanage-page\" >" . $pages . "</a ></li > ";
            else
                $output .= "<li class=\"page-item \"><a data-page=\"" . ($pages - 1) . "\" class=\"page-link iprmanage-page\">" . $pages . "</a></li>";
        }
        if ($page < $pages) {
            $output .= "<li class=\"page-item\"><a data-page=\"" . $page . "\" class=\"page-link iprmanage-page\"><span>»</span></a></li>";
        }

    }

    $output .= "</ul></nav>";
    return $output;
}

die();
