<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width,height=device-height">
	<meta name="robots" content="none">
	<?php echo $this->Html->css('index.css?'.date('YmdHis')); ?>
	<?php if($this->Html->isAndroid()): ?>
		<?php echo $this->Html->css('android.css?'.date('YmdHis')); ?>
	<?php endif; ?>
	<link rel="apple-touch-icon" href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/maoshift_favi.png" />
	<link rel="shortcut icon" href="favicon.ico" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<?php echo $this->Html->script('jquery.cookie.js'); ?>
	<?php echo $this->Html->script('swipe.js'); ?>
	<?php echo $this->Html->script('index.js'); ?>
	<title>
		<?php echo Configure::read('SITE_TITLE'); ?>
	</title>
	<?php
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body class="<?php echo (isset($season) || isset($season_no))? $season.$season_no:''; ?>">
	<?php echo $this->fetch('content'); ?>
</body>
</html>
