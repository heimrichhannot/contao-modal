<?php

$dc = &$GLOBALS['TL_DCA']['tl_content'];

/**
 * Dynamically add the permission check and parent table
 */
if (Input::get('do') == 'modal') {
	$dc['config']['ptable']                                = 'tl_modal';
	$dc['config']['onload_callback'][]                     = array('tl_content_modal', 'checkPermission');
	$dc['list']['operations']['toggle']['button_callback'] = array('tl_content_modal', 'toggleIcon');
}

$dc['config']['onload_callback'][] = array('tl_content_modal', 'modifyPalette');

/**
 * Subpalettes
 */
$dc['subpalettes']['source_modal'] = 'modal,jumpTo,target';

/**
 * Fields
 */
$arrFields = array
(
	'modal' => array
	(
		'label'            => &$GLOBALS['TL_LANG']['tl_content']['modal'],
		'exclude'          => true,
		'search'           => true,
		'inputType'        => 'select',
		'foreignKey'       => 'tl_modal.title',
		'options_callback' => array('HeimrichHannot\Modal\Backend\ContentBackend', 'getModalOptions'),
		'eval'             => array('tl_class' => 'w50 clr', 'mandatory' => true, 'includeBlankOption' => true, 'chosen' => true),
		'sql'              => "int(10) unsigned NOT NULL default '0'",
		'relation'         => array('type' => 'belongsTo', 'load' => 'lazy'),
	),
);

$dc['fields'] = array_merge($dc['fields'], $arrFields);


class tl_content_modal extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	public function modifyPalette(DataContainer $dc)
	{
		$objElement = \ContentModel::findByPk($dc->id);

		if($objElement === null || $objElement->type != 'linkteaser' || $objElement->source != 'modal')
		{
			return false;
		}

		$GLOBALS['TL_DCA']['tl_content']['fields']['jumpTo']['label'] = &$GLOBALS['TL_LANG']['tl_content']['modalJumpTo'];
		$GLOBALS['TL_DCA']['tl_content']['fields']['jumpTo']['eval']['mandatory'] = false;
		$GLOBALS['TL_DCA']['tl_content']['fields']['jumpTo']['eval']['tl_class'] = 'clr w50';

	}


	/**
	 * Check permissions to edit table tl_content
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin) {
			return;
		}

		// Set the root IDs
		if (!is_array($this->User->modals) || empty($this->User->modals)) {
			$root = array(0);
		} else {
			$root = $this->User->modals;
		}

		// Check the current action
		switch (Input::get('act')) {
			case 'paste':
				// Allow
				break;

			case '': // empty
			case 'create':
			case 'select':
				// Check access to the modal item
				if (!$this->checkAccessToElement(CURRENT_ID, $root, true)) {
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
			case 'cutAll':
			case 'copyAll':
				// Check access to the parent element if a content element is moved
				if ((Input::get('act') == 'cutAll' || Input::get('act') == 'copyAll') && !$this->checkAccessToElement(Input::get('pid'), $root, (Input::get('mode') == 2))) {
					$this->redirect('contao/main.php?act=error');
				}

				$objCes = $this->Database->prepare("SELECT id FROM tl_content WHERE ptable='tl_modal' AND pid=?")
					->execute(CURRENT_ID);

				$session                   = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objCes->fetchEach('id'));
				$this->Session->setData($session);
				break;

			case 'cut':
			case 'copy':
				// Check access to the parent element if a content element is moved
				if (!$this->checkAccessToElement(Input::get('pid'), $root, (Input::get('mode') == 2))) {
					$this->redirect('contao/main.php?act=error');
				}
			// NO BREAK STATEMENT HERE

			default:
				// Check access to the content element
				if (!$this->checkAccessToElement(Input::get('id'), $root)) {
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Check access to a particular content element
	 *
	 * @param integer $id
	 * @param array   $root
	 * @param boolean $blnIsPid
	 *
	 * @return boolean
	 */
	protected function checkAccessToElement($id, $root, $blnIsPid = false)
	{
		if ($blnIsPid) {
			$objArchive = $this->Database->prepare("SELECT a.id, m.id AS mid FROM tl_modal m, tl_modal_archive a WHERE m.id=? AND m.pid=a.id")
				->limit(1)
				->execute($id);
		} else {
			$objArchive = $this->Database->prepare("SELECT a.id, m.id AS mid FROM tl_content c, tl_modal m, tl_modal_archive a WHERE c.id=? AND c.pid=m.id AND m.pid=a.id")
				->limit(1)
				->execute($id);
		}

		// Invalid ID
		if ($objArchive->numRows < 1) {
			$this->log('Invalid modal content element ID ' . $id, __METHOD__, TL_ERROR);

			return false;
		}

		// The modal archive is not mounted
		if (!in_array($objArchive->id, $root)) {
			$this->log('Not enough permissions to modify modal ID ' . $objArchive->mid . ' in modal archive ID ' . $objArchive->id, __METHOD__, TL_ERROR);

			return false;
		}

		return true;
	}


	/**
	 * Return the "toggle visibility" button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid'))) {
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->hasAccess('tl_content::invisible', 'alexf')) {
			return '';
		}

		$href .= '&amp;id=' . Input::get('id') . '&amp;tid=' . $row['id'] . '&amp;state=' . $row['invisible'];

		if ($row['invisible']) {
			$icon = 'invisible.gif';
		}

		return '<a href="' . $this->addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label, 'data-state="' . ($row['invisible'] ? 0 : 1) . '"')
			   . '</a> ';
	}


	/**
	 * Toggle the visibility of an element
	 *
	 * @param integer       $intId
	 * @param boolean       $blnVisible
	 * @param DataContainer $dc
	 */
	public function toggleVisibility($intId, $blnVisible, DataContainer $dc = null)
	{
		// Set the ID and action
		Input::setGet('id', $intId);
		Input::setGet('act', 'toggle');

		if ($dc) {
			$dc->id = $intId; // see #8043
		}

		$this->checkPermission();

		// Check the field access
		if (!$this->User->hasAccess('tl_content::invisible', 'alexf')) {
			$this->log('Not enough permissions to publish/unpublish content element ID "' . $intId . '"', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// The onload_callbacks vary depending on the dynamic parent table (see #4894)
		if (is_array($GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'])) {
			foreach ($GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'] as $callback) {
				if (is_array($callback)) {
					$this->import($callback[0]);
					$this->{$callback[0]}->{$callback[1]}(($dc ?: $this));
				} elseif (is_callable($callback)) {
					$callback(($dc ?: $this));
				}
			}
		}

		// Check permissions to publish
		if (!$this->User->hasAccess('tl_content::invisible', 'alexf')) {
			$this->log('Not enough permissions to show/hide content element ID "' . $intId . '"', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$objVersions = new Versions('tl_content', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_content']['fields']['invisible']['save_callback'])) {
			foreach ($GLOBALS['TL_DCA']['tl_content']['fields']['invisible']['save_callback'] as $callback) {
				if (is_array($callback)) {
					$this->import($callback[0]);
					$blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, ($dc ?: $this));
				} elseif (is_callable($callback)) {
					$blnVisible = $callback($blnVisible, ($dc ?: $this));
				}
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_content SET tstamp=" . time() . ", invisible='" . ($blnVisible ? '' : 1) . "' WHERE id=?")
			->execute($intId);

		$objVersions->create();
		$this->log('A new version of record "tl_content.id=' . $intId . '" has been created' . $this->getParentEntries('tl_content', $intId), __METHOD__, TL_GENERAL);
	}
}
