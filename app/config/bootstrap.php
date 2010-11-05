<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php
 *
 * This is an application wide file to load any function that is not used within a class
 * define. You can also use this to include or require any files in your application.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * App::build(array(
 *     'plugins' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
 *     'models' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
 *     'views' => array('/full/path/to/views/', '/next/full/path/to/views/'),
 *     'controllers' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
 *     'datasources' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
 *     'behaviors' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
 *     'components' => array('/full/path/to/components/', '/next/full/path/to/components/'),
 *     'helpers' => array('/full/path/to/helpers/', '/next/full/path/to/helpers/'),
 *     'vendors' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
 *     'shells' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
 *     'locales' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
 * ));
 *
 */

/**
 * As of 1.3, additional rules for the inflector are added below
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */


/**
 * Easy debug and die method.
 *
 */
function d($var = 'x', $skipDebugMode = false, $showSql = false) {
	if (Configure::read() > 0 || $skipDebugMode === true) {
		$calledFrom = debug_backtrace();
		echo '<strong>' . substr(str_replace(ROOT, '', $calledFrom[0]['file']), 1) . '</strong>';
		echo ' (line <strong>' . $calledFrom[0]['line'] . '</strong>)';
		echo "\n<pre class=\"cake-debug\">\n";
		$var = print_r($var, true);
		if ($showSql) {
			dsql();
		}
		echo $var . "\n</pre>\n";
		die;
	}
}


/**
 * Debug and show SQL
 */
function ds($var = 'x') {
	d($var, false, true);
}

function dsql() {
    $sources = ConnectionManager::sourceList();
    if (!isset($logs)):
        $logs = array();
        foreach ($sources as $source):
            $db =& ConnectionManager::getDataSource($source);
            if (!$db->isInterfaceSupported('getLog')):
                continue;
            endif;
            $logs[$source] = $db->getLog();
        endforeach;
    endif;

	App::import('Vendor', 'Geshi');
	$geshi = new GeSHi('', 'mysql');
	$geshi->set_header_type(GESHI_HEADER_DIV);
	$geshi->enable_keyword_links(false);

    foreach ($logs as $source => $logInfo) {
        $text = $logInfo['count'] > 1 ? 'queries' : 'query';
        printf(
            '<table class="cake-sql-log" id="cakeSqlLog_%s" summary="Cake SQL Log" cellspacing="0" border = "0">',
            preg_replace('/[^A-Za-z0-9_]/', '_', uniqid(time(), true))
        );
        printf('<caption>(%s) %s %s took %s ms</caption>', $source, $logInfo['count'], $text, $logInfo['time']);
        echo '
            <thead>
                <tr><th>Nr</th><th>Query</th><th>Error</th><th>Affected</th><th>Num. rows</th><th>Took (ms)</th></tr>
            </thead>
            <tbody>
            ';
        foreach ($logInfo['log'] as $k => $i) {
			$geshi->set_source($i['query']);
            echo "<tr><td>" . ($k + 1) . "</td><td>" . $geshi->parse_code() . "</td><td>{$i['error']}</td><td style = \"text-align: right\">{$i['affected']}</td><td style = \"text-align: right\">{$i['numRows']}</td><td style = \"text-align: right\">{$i['took']}</td></tr>\n";
        }
        echo '</tbody></table>';
	}
}

?>