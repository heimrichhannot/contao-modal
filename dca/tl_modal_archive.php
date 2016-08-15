<?php

$GLOBALS['TL_DCA']['tl_modal_archive'] = array
(
	'config'      => array
	(
		'dataContainer'     => 'Table',
		'ctable'            => array('tl_modal'),
		'switchToEdit'      => true,
		'enableVersioning'  => false,
		'onload_callback'   => array
		(
			array('tl_modal_archive', 'checkPermission'),
		),
		'onsubmit_callback' => array
		(
			array('HeimrichHannot\Haste\Dca\General', 'setDateAdded'),
		),
		'sql'               => array
		(
			'keys' => array
			(
				'id' => 'primary',
			),
		),
	),
	'list'        => array
	(
		'label'             => array
		(
			'fields' => array('title'),
			'format' => '%s',
		),
		'sorting'           => array
		(
			'mode'        => 1,
			'fields'      => array('title'),
			'panelLayout' => 'filter;search,limit',
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
				'label' => &$GLOBALS['TL_LANG']['tl_modal_archive']['edit'],
				'href'  => 'table=tl_modal',
				'icon'  => 'edit.gif',
			),
			'editheader' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_modal_archive']['editheader'],
				'href'            => 'act=edit',
				'icon'            => 'header.gif',
				'button_callback' => array('tl_modal_archive', 'editHeader'),
			),
			'copy'       => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_modal_archive']['copy'],
				'href'            => 'act=copy',
				'icon'            => 'copy.gif',
				'button_callback' => array('tl_modal_archive', 'copyArchive'),
			),
			'delete'     => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_modal_archive']['copy'],
				'href'            => 'act=delete',
				'icon'            => 'delete.gif',
				'attributes'      => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
				'button_callback' => array('tl_modal_archive', 'deleteArchive'),
			),
			'show'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_modal_archive']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif',
			),
		),
	),
	'palettes'    => array
	(
		'__selector__' => array('customModal'),
		'default'      => '{general_legend},title;{expert_legend},customModal;',
	),
	'subpalettes' => array
	(
		'customModal' => 'modal',
	),
	'fields'      => array
	(
		'id'          => array
		(
			'sql' => "int(10) unsigned NOT NULL auto_increment",
		),
		'tstamp'      => array
		(
			'label' => &$GLOBALS['TL_LANG']['tl_modal_archive']['tstamp'],
			'sql'   => "int(10) unsigned NOT NULL default '0'",
		),
		'dateAdded'   => array
		(
			'label'   => &$GLOBALS['TL_LANG']['MSC']['dateAdded'],
			'sorting' => true,
			'flag'    => 6,
			'eval'    => array('rgxp' => 'datim', 'doNotCopy' => true),
			'sql'     => "int(10) unsigned NOT NULL default '0'",
		),
		'title'       => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_modal_archive']['title'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array('mandatory' => true, 'tl_class' => 'w50'),
			'sql'       => "varchar(255) NOT NULL default ''",
		),
		'customModal' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_modal_archive']['customModal'],
			'exclude'   => true,
			'filter'    => true,
			'inputType' => 'checkbox',
			'eval'      => array('submitOnChange' => true),
			'sql'       => "char(1) NOT NULL default ''",
		),
		'modal'       => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_modal_archive']['modal'],
			'exclude'          => true,
			'inputType'        => 'select',
			'options_callback' => array('HeimrichHannot\Modal\Backend\LayoutBackend', 'getModalOptions'),
			'reference'        => &$GLOBALS['TL_LANG']['modals'],
			'eval'             => array('includeBlankOption' => true, 'mandatory' => true),
			'sql'              => "varchar(64) NOT NULL default ''",
		),
	),
);

class tl_modal_archive extends \Backend
{

	public function checkPermission()
	{
		$objUser     = \BackendUser::getInstance();
		$objSession  = \Session::getInstance();
		$objDatabase = \Database::getInstance();

		if ($objUser->isAdmin) {
			return;
		}

		// Set root IDs
		if (!is_array($objUser->s) || empty($objUser->s)) {
			$root = array(0);
		} else {
			$root = $objUser->s;
		}

		$GLOBALS['TL_DCA']['tl_modal_archive']['list']['sorting']['root'] = $root;

		// Check permissions to add archives
		if (!$objUser->hasAccess('create', 'p')) {
			$GLOBALS['TL_DCA']['tl_modal_archive']['config']['closed'] = true;
		}

		// Check current action
		switch (\Input::get('act')) {
			case 'create':
			case 'select':
				// Allow
				break;

			case 'edit':
				// Dynamically add the record to the user profile
				if (!in_array(\Input::get('id'), $root)) {
					$arrNew = $objSession->get('new_records');

					if (is_array($arrNew['tl_modal_archive']) && in_array(\Input::get('id'), $arrNew['tl_modal_archive'])) {
						// Add permissions on user level
						if ($objUser->inherit == 'custom' || !$objUser->groups[0]) {
							$objUser = $objDatabase->prepare("SELECT s, p FROM tl_user WHERE id=?")
								->limit(1)
								->execute($objUser->id);

							$arrModulep = deserialize($objUser->p);

							if (is_array($arrModulep) && in_array('create', $arrModulep)) {
								$arrModules   = deserialize($objUser->s);
								$arrModules[] = \Input::get('id');

								$objDatabase->prepare("UPDATE tl_user SET s=? WHERE id=?")
									->execute(serialize($arrModules), $objUser->id);
							}
						} // Add permissions on group level
						elseif ($objUser->groups[0] > 0) {
							$objGroup = $objDatabase->prepare("SELECT s, p FROM tl_user_group WHERE id=?")
								->limit(1)
								->execute($objUser->groups[0]);

							$arrModulep = deserialize($objGroup->p);

							if (is_array($arrModulep) && in_array('create', $arrModulep)) {
								$arrModules   = deserialize($objGroup->s);
								$arrModules[] = \Input::get('id');

								$objDatabase->prepare("UPDATE tl_user_group SET s=? WHERE id=?")
									->execute(serialize($arrModules), $objUser->groups[0]);
							}
						}

						// Add new element to the user object
						$root[]     = \Input::get('id');
						$objUser->s = $root;
					}
				}
			// No break;

			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array(\Input::get('id'), $root) || (\Input::get('act') == 'delete' && !$objUser->hasAccess('delete', 'p'))) {
					$this->log('Not enough permissions to ' . \Input::get('act') . ' modal_archive ID "' . \Input::get('id') . '"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $objSession->getData();
				if (\Input::get('act') == 'deleteAll' && !$objUser->hasAccess('delete', 'p')) {
					$session['CURRENT']['IDS'] = array();
				} else {
					$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $root);
				}
				$objSession->setData($session);
				break;

			default:
				if (strlen(\Input::get('act'))) {
					$this->log('Not enough permissions to ' . \Input::get('act') . ' modal_archive archives', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}

	public function editHeader($row, $href, $label, $title, $icon, $attributes)
	{
		return \BackendUser::getInstance()->canEditFieldsOf('tl_modal_archive')
			? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> '
			: \Image::getHtml(
				preg_replace('/\.gif$/i', '_.gif', $icon)
			) . ' ';
	}

	public function copyArchive($row, $href, $label, $title, $icon, $attributes)
	{
		return \BackendUser::getInstance()->hasAccess('create', 'p') ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>'
																	   . Image::getHtml($icon, $label) . '</a> ' : \Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)) . ' ';
	}

	public function deleteArchive($row, $href, $label, $title, $icon, $attributes)
	{
		return \BackendUser::getInstance()->hasAccess('delete', 'p') ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>'
																	   . Image::getHtml($icon, $label) . '</a> ' : \Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)) . ' ';
	}
}
