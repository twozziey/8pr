<?php
	session_start();
	include("./settings/connect_datebase.php");
	
    if(isset($_SESSION["user"])) {
        if($_SESSION['user'] != -1) {
            $user_query = $mysqli->query("SELECT * FROM `users` WHERE `id` = ".$_SESSION['user']);
            while($user_read = $user_query->fetch_row()) {
                if($user_read[3] == 0) header("Location: user.php");
                else if($user_read[3] == 1) header("Location:admin.php");
                exit;
            }
        }
    }
    if (!isset($_SESSION['mail']) || !isset($_SESSION['preuser'])) {
		header("Location: login.php");
        exit;
 	}

    $code = rand(100000, 999999);
    $_SESSION['code'] = $code;

    mail($_SESSION['mail'], 'Код для подтверждения авторизации', 'Код для подтверждения: '.$code);
    unset($_SESSION['mail']);
?>
<html>
	<head> 
		<meta charset="utf-8">
		<title> Подтверждение авторизации </title>
		
		<script src="https://code.jquery.com/jquery-1.8.3.js"></script>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="top-menu">
			<a href=#><img src = "img/logo1.png"/></a>
			<div class="name">
				<a href="index.php">
					<div class="subname">БЗОПАСНОСТЬ  ВЕБ-ПРИЛОЖЕНИЙ</div>
					Пермский авиационный техникум им. А. Д. Швецова
				</a>
			</div>
		</div>
		<div class="space"> </div>
		<div class="main">
			<div class="content">
				<div class = "login">
					<div class="name">Подтверждение авторизации</div>
				
					<div class = "sub-name">Код отправленный на почту:</div>
					<input name="_code" type="text" placeholder="123456" maxlength="6" autocomplete="off" onkeypress="return PressToEnter(event)"/>
					<input type="button" class="button" value="Войти" onclick="CheckCode()"/>
					<img src = "img/loading.gif" class="loading"/>
				</div>
				
				<div class="footer">
					© КГАПОУ "Авиатехникум", 2020
					<a href=#>Конфиденциальность</a>
					<a href=#>Условия</a>
				</div>
			</div>
		</div>
		
		<script>
			function CheckCode() {
				var codeField = document.getElementsByName("_code")[0];
                var loading = document.getElementsByClassName("loading")[0];
                var button = document.getElementsByClassName("button")[0];
                var _code = codeField.value.trim();

                if(_code.length != 6 || !/^\d{6}$/.test(_code)) {
                    alert("Введите 6-значный код из письма");
                    codeField.focus();
                    return;
                }

				loading.style.display = "block";
                button.disabled = true;
				button.className = "button_diactive";
				
				var data = new FormData();
				data.append("code", _code);
				
				// AJAX запрос
				$.ajax({
					url         : 'ajax/check_code.php',
					type        : 'POST', // важно!
					data        : data,
					cache       : false,
					dataType    : 'html',
					processData : false,
					contentType : false, 
					success: function (_data) {
						console.log("Авторизация прошла успешно, id: " +_data);
						if(_data == "") {
							loading.style.display = "none";
							button.className = "button";
							alert("Логин или пароль не верный.");
						} else {
							localStorage.setItem("token", _data);
							location.reload();
							loading.style.display = "none";
							button.className = "button";
						}
					},
					// функция ошибки
					error: function( ){
						console.log('Системная ошибка!');
						loading.style.display = "none";
						button.className = "button";
					}
				});
			}
			
			function PressToEnter(e) {
				if (e.keyCode == 13) {
					var _login = document.getElementsByName("_login")[0].value;
					var _password = document.getElementsByName("_password")[0].value;
					
					if(_password != "") {
						if(_login != "") {
							LogIn();
						}
					}
				}
			}
			
		</script>
	</body>
</html>