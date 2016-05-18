<?php

$GLOBALS['TL_DCA']['tl_modal'] = array
(
	'config'      => array
	(
		'dataContainer'     => 'Table',
		'ptable'            => 'tl_modal_archive',
		'ctable'            => array('tl_content'),
		'enableVersioning'  => true,
		'onload_callback'   => array
		(
			array('tl_modal', 'checkPermission'),
		),
		'onsubmit_callback' => array
		(
			array('HeimrichHannot\Haste\Dca\General', 'setDateAdded'),
		),
		'sql'               => array
		(
			'keys' => array
			(
				'id'                       => 'primary',
				'pid,start,stop,published' => 'index',
			),
		),
	),
	'list'        => array
	(
		'label'             => array
		(
			'fields' => array('id'),
			'format' => '%s',
		),
		'sorting'           => array
		(
			'mode'                  => 4,
			'fields'                => array('title'),
			'headerFields'          => array('title', 'tstamp'),
			'panelLayout'           => 'filter;search,limit',
			'child_record_callback' => array('tl_modal', 'listChildren'),
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();"',
			),
		),
		'operations'        => array
		(
			'edit'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_modal']['edit'],
				'href'  => 'table=tl_content',
				'icon'  => 'edit.gif',
			),
			'editheader' => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_modal']['editmeta'],
				'href'  => 'act=edit',
				'icon'  => 'header.gif',
			),
			'copy'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_modal']['copy'],
				'href'  => 'act=copy',
				'icon'  => 'copy.gif',
			),
			'delete'     => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_modal']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
			),
			'toggle'     => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_modal']['toggle'],
				'icon'            => 'visible.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback' => array('tl_modal', 'toggleIcon'),
			),
			'show'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_modal']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif',
			),
		),
	),
	'palettes'    => array
	(
		'__selector__' => array('customModal', 'customHeader', 'addFooter', 'published'),
		'default'      => '{general_legend},title,alias;{header_legend},headline,customHeader;{footer_legend},addFooter;{expert_legend},customModal;{publish_legend},published;',
	),
	'subpalettes' => array
	(
		'published'    => 'start,stop',
		'customModal'  => 'modal',
		'customHeader' => 'header',
		'addFooter'    => 'footer',
	),
	'fields'      => array
	(
		'id'           => array
		(
			'sql' => "int(10) unsigned NOT NULL auto_increment",
		),
		'pid'          => array
		(
			'foreignKey' => 'tl_modal_archive.title',
			'sql'        => "int(10) unsigned NOT NULL default '0'",
			'relation'   => array('type' => 'belongsTo', 'load' => 'eager'),
		),
		'tstamp'       => array
		(
			'label' => &$GLOBALS['TL_LANG']['tl_modal']['tstamp'],
			'sql'   => "int(10) unsigned NOT NULL default '0'",
		),
		'dateAdded'    => array
		(
			'label'   => &$GLOBALS['TL_LANG']['MSC']['dateAdded'],
			'sorting' => true,
			'flag'    => 6,
			'eval'    => array('rgxp' => 'datim', 'doNotCopy' => true),
			'sql'     => "int(10) unsigned NOT NULL default '0'",
		),
		'title'        => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_modal']['title'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array('mandatory' => true, 'tl_class' => 'w50'),
			'sql'       => "varchar(255) NOT NULL default ''",
		),
		'alias'        => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_modal']['alias'],
			'exclude'       => true,
			'inputType'     => 'text',
			'search'        => true,
			'eval'          => array('rgxp' => 'folderalias', 'doNotCopy' => true, 'maxlength' => 128, 'tl_class' => 'w50'),
			'save_callback' => array
			(
				array('tl_modal', 'generateAlias'),
			),
			'sql'           => "varchar(128) COLLATE utf8_bin NOT NULL default ''",
		),
		'customModal'  => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_modal']['customModal'],
			'exclude'   => true,
			'filter'    => true,
			'inputType' => 'checkbox',
			'eval'      => array('submitOnChange' => true),
			'sql'       => "char(1) NOT NULL default ''",
		),
		'modal'        => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_modal']['modal'],
			'exclude'          => true,
			'inputType'        => 'select',
			'options_callback' => array('HeimrichHannot\Modal\Backend\Layout', 'getModalOptions'),
			'reference'        => &$GLOBALS['TL_LANG']['modals'],
			'eval'             => array('includeBlankOption' => true, 'mandatory' => true),
			'sql'              => "varchar(64) NOT NULL default ''",
		),
		'headline'     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_modal']['headline'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'inputUnit',
			'options'   => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
			'eval'      => array('maxlength' => 200),
			'sql'       => "varchar(255) NOT NULL default ''",
		),
		'customHeader' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_modal']['customHeader'],
			'exclude'   => true,
			'filter'    => true,
			'inputType' => 'checkbox',
			'eval'      => array('submitOnChange' => true),
			'sql'       => "char(1) NOT NULL default ''",
		),
		'header'       => array
		(
			'label'       => &$GLOBALS['TL_LANG']['tl_modal']['header'],
			'exclude'     => true,
			'search'      => true,
			'inputType'   => 'textarea',
			'eval'        => array('mandatory' => true, 'allowHtml' => true, 'class' => 'monospace', 'rte' => 'ace|html', 'helpwizard' => true),
			'explanation' => 'insertTags',
			'sql'         => "mediumtext NULL",
		),
		'addFooter'    => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_modal']['addFooter'],
			'exclude'   => true,
			'filter'    => true,
			'inputType' => 'checkbox',
			'eval'      => array('submitOnChange' => true),
			'sql'       => "char(1) NOT NULL default ''",
		),
		'footer'       => array
		(
			'label'       => &$GLOBALS['TL_LANG']['tl_modal']['footer'],
			'exclude'     => true,
			'search'      => true,
			'inputType'   => 'textarea',
			'eval'        => array('mandatory' => true, 'allowHtml' => true, 'class' => 'monospace', 'rte' => 'ace|html', 'helpwizard' => true),
			'explanation' => 'insertTags',
			'sql'         => "mediumtext NULL",
		),
		'published'    => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_modal']['published'],
			'exclude'   => true,
			'filter'    => true,
			'inputType' => 'checkbox',
			'eval'      => array('doNotCopy' => true, 'submitOnChange' => true),
			'sql'       => "char(1) NOT NULL default '0'",
		),
		'start'        => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_modal']['start'],
			'exclude'   => true,
			'inputType' => 'text',
			'eval'      => array('rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'),
			'sql'       => "varchar(10) NOT NULL default ''",
		),
		'stop'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_modal']['stop'],
			'exclude'   => true,
			'inputType' => 'text',
			'eval'      => array('rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'),
			'sql'       => "varchar(10) NOT NULL default ''",
		),
	),
);


class tl_modal extends \Backend
{
	/**
	 * Auto-generate a page alias if it has not been set yet
	 *
	 * @param mixed         $varValue
	 * @param DataContainer $dc
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		return HeimrichHannot\Haste\Dca\General::generateAlias($varValue, $dc->id, $dc->table, $dc->activeRecord->title);
	}


	public function listChildren($arrRow)
	{
		return '<div class="tl_content_left">' . ($arrRow['title'] ?: $arrRow['id']) . ' <span style="color:#b3b3b3; padding-left:3px">[' .
			   \Date::parse(Config::get('datimFormat'), trim($arrRow['dateAdded'])) . ']</span></div>';
	}

	public function checkPermission()
	{
		$objUser     = \BackendUser::getInstance();
		$objSession  = \Session::getInstance();
		$objDatabase = \Database::getInstance();

		if ($objUser->isAdmin) {
			return;
		}

		// Set the root IDs
		if (!is_array($objUser->s) || empty($objUser->s)) {
			$root = array(0);
		} else {
			$root = $objUser->s;
		}

		$id = strlen(Input::get('id')) ? Input::get('id') : CURRENT_ID;

		// Check current action
		switch (Input::get('act')) {
			case 'paste':
				// Allow
				break;

			case 'create':
				if (!strlen(Input::get('pid')) || !in_array(Input::get('pid'), $root)) {
					\Controller::log('Not enough permissions to create modal items in modal archive ID "' . Input::get('pid') . '"', 'tl_modal checkPermission', TL_ERROR);
					\Controller::redirect('contao/main.php?act=error');
				}
				break;

			case 'cut':
			case 'copy':
				if (!in_array(Input::get('pid'), $root)) {
					\Controller::log(
						'Not enough permissions to ' . Input::get('act') . ' modal item ID "' . $id . '" to modal archive ID "' . Input::get('pid') . '"',
						'tl_modal checkPermission',
						TL_ERROR
					);
					\Controller::redirect('contao/main.php?act=error');
				}
			// NO BREAK STATEMENT HERE

			case 'edit':
			case 'show':
			case 'delete':
			case 'toggle':
			case 'feature':
				$objArchive = $objDatabase->prepare("SELECT pid FROM tl_modal WHERE id=?")
					->limit(1)->execute($id);

				if ($objArchive->numRows < 1) {
					\Controller::log('Invalid modal item ID "' . $id . '"', 'tl_modal checkPermission', TL_ERROR);
					\Controller::redirect('contao/main.php?act=error');
				}

				if (!in_array($objArchive->pid, $root)) {
					\Controller::log(
						'Not enough permissions to ' . Input::get('act') . ' modal item ID "' . $id . '" of modal archive ID "' . $objArchive->pid . '"',
						'tl_modal checkPermission',
						TL_ERROR
					);
					\Controller::redirect('contao/main.php?act=error');
				}
				break;

			case 'select':
			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
			case 'cutAll':
			case 'copyAll':
				if (!in_array($id, $root)) {
					\Controller::log('Not enough permissions to access modal archive ID "' . $id . '"', 'tl_modal checkPermission', TL_ERROR);
					\Controller::redirect('contao/main.php?act=error');
				}

				$objArchive = $objDatabase->prepare("SELECT id FROM tl_modal WHERE pid=?")->execute($id);

				if ($objArchive->numRows < 1) {
					\Controller::log('Invalid modal archive ID "' . $id . '"', 'tl_modal checkPermission', TL_ERROR);
					\Controller::redirect('contao/main.php?act=error');
				}

				$session                   = $objSession->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objArchive->fetchEach('id'));
				$objSession->setData($session);
				break;

			default:
				if (strlen(Input::get('act'))) {
					\Controller::log('Invalid command "' . Input::get('act') . '"', 'tl_modal checkPermission', TL_ERROR);
					\Controller::redirect('contao/main.php?act=error');
				} elseif (!in_array($id, $root)) {
					\Controller::log('Not enough permissions to access modal archive ID ' . $id, 'tl_modal checkPermission', TL_ERROR);
					\Controller::redirect('contao/main.php?act=error');
				}
				break;
		}
	}

	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		$objUser = \BackendUser::getInstance();

		if (strlen(Input::get('tid'))) {
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1));
			\Controller::redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$objUser->isAdmin && !$objUser->hasAccess('tl_modal::published', 'alexf')) {
			return '';
		}

		$href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

		if (!$row['published']) {
			$icon = 'invisible.gif';
		}

		return '<a href="' . $this->addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ';
	}

	public function toggleVisibility($intId, $blnVisible)
	{
		$objUser     = \BackendUser::getInstance();
		$objDatabase = \Database::getInstance();

		// Check permissions to publish
		if (!$objUser->isAdmin && !$objUser->hasAccess('tl_modal::published', 'alexf')) {
			\Controller::log('Not enough permissions to publish/unpublish item ID "' . $intId . '"', 'tl_modal toggleVisibility', TL_ERROR);
			\Controller::redirect('contao/main.php?act=error');
		}

		$objVersions = new Versions('tl_modal', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_modal']['fields']['published']['save_callback'])) {
			foreach ($GLOBALS['TL_DCA']['tl_modal']['fields']['published']['save_callback'] as $callback) {
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$objDatabase->prepare("UPDATE tl_modal SET tstamp=" . time() . ", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
			->execute($intId);

		$objVersions->create();
		\Controller::log('A new version of record "tl_modal.id=' . $intId . '" has been created' . $this->getParentEntries('tl_modal', $intId), 'tl_modal toggleVisibility()', TL_GENERAL);
	}

}
