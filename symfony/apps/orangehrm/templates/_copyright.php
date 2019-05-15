<?php 
$rootPath = realpath(dirname(__FILE__)."/../../../../");

if (@include_once $rootPath."/lib/confs/sysConf.php") {
    $conf = new sysConf();
    $version = $conf->getVersion();
}
$prodName = 'iTeQ HRM Solutions';
$copyrightYear = date('Y');
$version = '1.0'
?>
<?php echo $prodName . ' ' . $version;?> - &copy; <?php echo $copyrightYear;?>
<!--&copy; $copyrightYear - --><?php //echo $copyrightYear;?><!-- <a href="http://www.orangehrm.com" target="_blank">OrangeHRM, Inc</a>. All rights reserved.-->
