<?php
/** PHPExcel root directory */
if (!defined('PHPEXCEL_ROOT')) {
    define('PHPEXCEL_ROOT', THINK_PATH.'Common/Class/phpExcel/');
    require(PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php');
}


/**
 * PHPExcel
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel
{
    /**
     * Unique ID
     *
     * @var string
     */
    private static $_uniqueID;

    /**
     * Document properties
     *
     * @var PHPExcel_DocumentProperties
     */
    private static $_properties;

    /**
     * Document security
     *
     * @var PHPExcel_DocumentSecurity
     */
    private static $_security;

    /**
     * Collection of Worksheet objects
     *
     * @var PHPExcel_Worksheet[]
     */
    private static $_workSheetCollection = array();

    /**
	 * Calculation Engine
	 *
	 * @var PHPExcel_Calculation
	 */
	private static $_calculationEngine = NULL;

    /**
     * Active sheet index
     *
     * @var int
     */
    private static $_activeSheetIndex = 0;

    /**
     * Named ranges
     *
     * @var PHPExcel_NamedRange[]
     */
    private static $_namedRanges = array();

    /**
     * CellXf supervisor
     *
     * @var PHPExcel_Style
     */
    private static $_cellXfSupervisor;

    /**
     * CellXf collection
     *
     * @var PHPExcel_Style[]
     */
    private static $_cellXfCollection = array();

    /**
     * CellStyleXf collection
     *
     * @var PHPExcel_Style[]
     */
    private static $_cellStyleXfCollection = array();

	/**
	* _hasMacros : this workbook have macros ?
	*
	* @var bool
	*/
	private static $_hasMacros = FALSE;

	/**
	* _macrosCode : all macros code (the vbaProject.bin file, this include form, code,  etc.), NULL if no macro
	*
	* @var binary
	*/
	private static $_macrosCode=NULL;
	/**
	* _macrosCertificate : if macros are signed, contains vbaProjectSignature.bin file, NULL if not signed
	*
	* @var binary
	*/
	private static $_macrosCertificate=NULL;

	/**
	* _ribbonXMLData : NULL if workbook is'nt Excel 2007 or not contain a customized UI
	*
	* @var NULL|string
	*/
	private static $_ribbonXMLData=NULL;

	/**
	* _ribbonBinObjects : NULL if workbook is'nt Excel 2007 or not contain embedded objects (picture(s)) for Ribbon Elements
	* ignored if $_ribbonXMLData is null
	*
	* @var NULL|array
	*/
	private static $_ribbonBinObjects=NULL;

	/**
	* The workbook has macros ?
	*
	* @return true if workbook has macros, false if not
	*/
	public static function hasMacros(){
		return self::$_hasMacros;
	}

	/**
	* Define if a workbook has macros
	*
	* @param true|false
	*/
	public static function setHasMacros($hasMacros=false){
		self::$_hasMacros=(bool)$hasMacros;
	}

	/**
	* Set the macros code
	*
	* @param binary string|null
	*/
	public static function setMacrosCode($MacrosCode){
		self::$_macrosCode=$MacrosCode;
		self::setHasMacros(!is_null($MacrosCode));
	}

	/**
	* Return the macros code
	*
	* @return binary|null
	*/
	public static function getMacrosCode(){
		return self::$_macrosCode;
	}

	/**
	* Set the macros certificate
	*
	* @param binary|null
	*/
	public static function setMacrosCertificate($Certificate=NULL){
		self::$_macrosCertificate=$Certificate;
	}

	/**
	* Is the project signed ?
	*
	* @return true|false
	*/
	public static function hasMacrosCertificate(){
		return !is_null(self::$_macrosCertificate);
	}

	/**
	* Return the macros certificate
	*
	* @return binary|null
	*/
	public static function getMacrosCertificate(){
		return self::$_macrosCertificate;
	}

	/**
	* Remove all macros, certificate from spreadsheet
	*
	* @param none
	* @return void
	*/
	public static function discardMacros(){
		self::$_hasMacros=false;
		self::$_macrosCode=NULL;
		self::$_macrosCertificate=NULL;
	}

	/**
	* set ribbon XML data
	*
	*/
	public static function setRibbonXMLData($Target=NULL, $XMLData=NULL){
		if(!is_null($Target) && !is_null($XMLData)){
			self::$_ribbonXMLData=array('target'=>$Target, 'data'=>$XMLData);
		}else{
			self::$_ribbonXMLData=NULL;
		}
	}

	/**
	* retrieve ribbon XML Data
	*
	* return string|null|array
	*/
	public static function getRibbonXMLData($What='all'){//we need some constants here...
		$ReturnData=NULL;
		$What=strtolower($What);
		switch($What){
		case 'all':
			$ReturnData=self::$_ribbonXMLData;
			break;
		case 'target':
		case 'data':
			if(is_array(self::$_ribbonXMLData) && array_key_exists($What,self::$_ribbonXMLData)){
				$ReturnData=self::$_ribbonXMLData[$What];
			}//else $ReturnData stay at null
			break;
		}//default: $ReturnData at null
		return $ReturnData;
	}

	/**
	* store binaries ribbon objects (pictures)
	*
	*/
	public static function setRibbonBinObjects($BinObjectsNames=NULL, $BinObjectsData=NULL){
		if(!is_null($BinObjectsNames) && !is_null($BinObjectsData)){
			self::$_ribbonBinObjects=array('names'=>$BinObjectsNames, 'data'=>$BinObjectsData);
		}else{
			self::$_ribbonBinObjects=NULL;
		}
	}
	/**
	* return the extension of a filename. Internal use for a array_map callback (php<5.3 don't like lambda function)
	*
	*/
	private static function _getExtensionOnly($ThePath){
		return pathinfo($ThePath, PATHINFO_EXTENSION);
	}

	/**
	* retrieve Binaries Ribbon Objects
	*
	*/
	public static function getRibbonBinObjects($What='all'){
		$ReturnData=NULL;
		$What=strtolower($What);
		switch($What){
		case 'all':
			return self::$_ribbonBinObjects;
			break;
		case 'names':
		case 'data':
			if(is_array(self::$_ribbonBinObjects) && array_key_exists($What, self::$_ribbonBinObjects)){
				$ReturnData=self::$_ribbonBinObjects[$What];
			}
			break;
		case 'types':
			if(is_array(self::$_ribbonBinObjects) && array_key_exists('data', self::$_ribbonBinObjects) && is_array(self::$_ribbonBinObjects['data'])){
				$tmpTypes=array_keys(self::$_ribbonBinObjects['data']);
				$ReturnData=array_unique(array_map(array(self,'_getExtensionOnly'), $tmpTypes));
			}else
				$ReturnData=array();//the caller want an array... not null if empty
			break;
		}
		return $ReturnData;
	}

	/**
	* This workbook have a custom UI ?
	*
	* @return true|false
	*/
	public static function hasRibbon(){
		return !is_null(self::$_ribbonXMLData);
	}

	/**
	* This workbook have additionnal object for the ribbon ?
	*
	* @return true|false
	*/
	public static function hasRibbonBinObjects(){
		return !is_null(self::$_ribbonBinObjects);
	}

	/**
     * Check if a sheet with a specified code name already exists
     *
     * @param string $pSheetCodeName  Name of the worksheet to check
     * @return boolean
     */
    public static function sheetCodeNameExists($pSheetCodeName)
    {
		return (self::getSheetByCodeName($pSheetCodeName) !== NULL);
    }

	/**
	 * Get sheet by code name. Warning : sheet don't have always a code name !
	 *
	 * @param string $pName Sheet name
	 * @return PHPExcel_Worksheet
	 */
	public static function getSheetByCodeName($pName = '')
	{
		$worksheetCount = count(self::$_workSheetCollection);
		for ($i = 0; $i < $worksheetCount; ++$i) {
			if (self::$_workSheetCollection[$i]->getCodeName() == $pName) {
				return self::$_workSheetCollection[$i];
			}
		}

		return null;
	}

	 /**
	 * Create a new PHPExcel with one Worksheet
	 */
	public static function init()
	{
		self::$_uniqueID = uniqid();
		self::$_calculationEngine	= PHPExcel_Calculation::getInstance(self);

		// Initialise worksheet collection and add one worksheet
		self::$_workSheetCollection = array();
		self::$_workSheetCollection[] = new PHPExcel_Worksheet(self);
		self::$_activeSheetIndex = 0;

        // Create document properties
        self::$_properties = new PHPExcel_DocumentProperties();

        // Create document security
        self::$_security = new PHPExcel_DocumentSecurity();

        // Set named ranges
        self::$_namedRanges = array();

        // Create the cellXf supervisor
        self::$_cellXfSupervisor = new PHPExcel_Style(true);
        self::$_cellXfSupervisor->bindParent(self);

        // Create the default style
        self::addCellXf(new PHPExcel_Style);
        self::addCellStyleXf(new PHPExcel_Style);
    }

    /**
     * Code to execute when this worksheet is unset()
     *
     */
    public static function __destruct() {
        PHPExcel_Calculation::unsetInstance(self);
        self::disconnectWorksheets();
    }    //    function __destruct()

    /**
     * Disconnect all worksheets from this PHPExcel workbook object,
     *    typically so that the PHPExcel object can be unset
     *
     */
    public static function disconnectWorksheets()
    {
    	$worksheet = NULL;
        foreach(self::$_workSheetCollection as $k => &$worksheet) {
            $worksheet->disconnectCells();
            self::$_workSheetCollection[$k] = null;
        }
        unset($worksheet);
        self::$_workSheetCollection = array();
    }

	/**
	 * Return the calculation engine for this worksheet
	 *
	 * @return PHPExcel_Calculation
	 */
	public static function getCalculationEngine()
	{
		return self::$_calculationEngine;
	}	//	function getCellCacheController()

    /**
     * Get properties
     *
     * @return PHPExcel_DocumentProperties
     */
    public static function getProperties()
    {
        return self::$_properties;
    }

    /**
     * Set properties
     *
     * @param PHPExcel_DocumentProperties    $pValue
     */
    public static function setProperties(PHPExcel_DocumentProperties $pValue)
    {
        self::$_properties = $pValue;
    }

    /**
     * Get security
     *
     * @return PHPExcel_DocumentSecurity
     */
    public static function getSecurity()
    {
        return self::$_security;
    }

    /**
     * Set security
     *
     * @param PHPExcel_DocumentSecurity    $pValue
     */
    public static function setSecurity(PHPExcel_DocumentSecurity $pValue)
    {
        self::$_security = $pValue;
    }

    /**
     * Get active sheet
     *
     * @return PHPExcel_Worksheet
     */
    public static function getActiveSheet()
    {
        return self::$_workSheetCollection[self::$_activeSheetIndex];
    }

    /**
     * Create sheet and add it to this workbook
     *
     * @param  int|null $iSheetIndex Index where sheet should go (0,1,..., or null for last)
     * @return PHPExcel_Worksheet
     * @throws PHPExcel_Exception
     */
    public static function createSheet($iSheetIndex = NULL)
    {
        $newSheet = new PHPExcel_Worksheet(self);
        self::addSheet($newSheet, $iSheetIndex);
        return $newSheet;
    }

    /**
     * Check if a sheet with a specified name already exists
     *
     * @param  string $pSheetName  Name of the worksheet to check
     * @return boolean
     */
    public static function sheetNameExists($pSheetName)
    {
        return (self::getSheetByName($pSheetName) !== NULL);
    }

    /**
     * Add sheet
     *
     * @param  PHPExcel_Worksheet $pSheet
     * @param  int|null $iSheetIndex Index where sheet should go (0,1,..., or null for last)
     * @return PHPExcel_Worksheet
     * @throws PHPExcel_Exception
     */
    public static function addSheet(PHPExcel_Worksheet $pSheet, $iSheetIndex = NULL)
    {
        if (self::sheetNameExists($pSheet->getTitle())) {
            throw new PHPExcel_Exception(
            	"Workbook already contains a worksheet named '{$pSheet->getTitle()}'. Rename this worksheet first."
            );
        }

        if($iSheetIndex === NULL) {
            if (self::$_activeSheetIndex < 0) {
                self::$_activeSheetIndex = 0;
            }
            self::$_workSheetCollection[] = $pSheet;
        } else {
            // Insert the sheet at the requested index
            array_splice(
                self::$_workSheetCollection,
                $iSheetIndex,
                0,
                array($pSheet)
                );

            // Adjust active sheet index if necessary
            if (self::$_activeSheetIndex >= $iSheetIndex) {
                ++self::$_activeSheetIndex;
            }
        }

        if ($pSheet->getParent() === null) {
            $pSheet->rebindParent(self);
        }

        return $pSheet;
    }

    /**
     * Remove sheet by index
     *
     * @param  int $pIndex Active sheet index
     * @throws PHPExcel_Exception
     */
    public static function removeSheetByIndex($pIndex = 0)
    {

        $numSheets = count(self::$_workSheetCollection);

        if ($pIndex > $numSheets - 1) {
            throw new PHPExcel_Exception(
            	"You tried to remove a sheet by the out of bounds index: {$pIndex}. The actual number of sheets is {$numSheets}."
            );
        } else {
            array_splice(self::$_workSheetCollection, $pIndex, 1);
        }
        // Adjust active sheet index if necessary
        if ((self::$_activeSheetIndex >= $pIndex) &&
            ($pIndex > count(self::$_workSheetCollection) - 1)) {
            --self::$_activeSheetIndex;
        }

    }

    /**
     * Get sheet by index
     *
     * @param  int $pIndex Sheet index
     * @return PHPExcel_Worksheet
     * @throws PHPExcel_Exception
     */
    public static function getSheet($pIndex = 0)
    {

        $numSheets = count(self::$_workSheetCollection);

        if ($pIndex > $numSheets - 1) {
            throw new PHPExcel_Exception(
            	"Your requested sheet index: {$pIndex} is out of bounds. The actual number of sheets is {$numSheets}."
           	);
        } else {
            return self::$_workSheetCollection[$pIndex];
        }
    }

    /**
     * Get all sheets
     *
     * @return PHPExcel_Worksheet[]
     */
    public static function getAllSheets()
    {
        return self::$_workSheetCollection;
    }

    /**
     * Get sheet by name
     *
     * @param  string $pName Sheet name
     * @return PHPExcel_Worksheet
     */
    public static function getSheetByName($pName = '')
    {
        $worksheetCount = count(self::$_workSheetCollection);
        for ($i = 0; $i < $worksheetCount; ++$i) {
            if (self::$_workSheetCollection[$i]->getTitle() === $pName) {
                return self::$_workSheetCollection[$i];
            }
        }

        return NULL;
    }

    /**
     * Get index for sheet
     *
     * @param  PHPExcel_Worksheet $pSheet
     * @return Sheet index
     * @throws PHPExcel_Exception
     */
    public static function getIndex(PHPExcel_Worksheet $pSheet)
    {
        foreach (self::$_workSheetCollection as $key => $value) {
            if ($value->getHashCode() == $pSheet->getHashCode()) {
                return $key;
            }
        }

        throw new PHPExcel_Exception("Sheet does not exist.");
    }

    /**
     * Set index for sheet by sheet name.
     *
     * @param  string $sheetName Sheet name to modify index for
     * @param  int $newIndex New index for the sheet
     * @return New sheet index
     * @throws PHPExcel_Exception
     */
    public static function setIndexByName($sheetName, $newIndex)
    {
        $oldIndex = self::getIndex(self::getSheetByName($sheetName));
        $pSheet = array_splice(
            self::$_workSheetCollection,
            $oldIndex,
            1
        );
        array_splice(
            self::$_workSheetCollection,
            $newIndex,
            0,
            $pSheet
        );
        return $newIndex;
    }

    /**
     * Get sheet count
     *
     * @return int
     */
    public static function getSheetCount()
    {
        return count(self::$_workSheetCollection);
    }

    /**
     * Get active sheet index
     *
     * @return int Active sheet index
     */
    public static function getActiveSheetIndex()
    {
        return self::$_activeSheetIndex;
    }

    /**
     * Set active sheet index
     *
     * @param  int $pIndex Active sheet index
     * @throws PHPExcel_Exception
     * @return PHPExcel_Worksheet
     */
    public static function setActiveSheetIndex($pIndex = 0)
    {
    		$numSheets = count(self::$_workSheetCollection);

        if ($pIndex > $numSheets - 1) {
            throw new PHPExcel_Exception(
            	"You tried to set a sheet active by the out of bounds index: {$pIndex}. The actual number of sheets is {$numSheets}."
            );
        } else {
            self::$_activeSheetIndex = $pIndex;
        }
        return self::getActiveSheet();
    }

    /**
     * Set active sheet index by name
     *
     * @param  string $pValue Sheet title
     * @return PHPExcel_Worksheet
     * @throws PHPExcel_Exception
     */
    public static function setActiveSheetIndexByName($pValue = '')
    {
        if (($worksheet = self::getSheetByName($pValue)) instanceof PHPExcel_Worksheet) {
            self::setActiveSheetIndex(self::getIndex($worksheet));
            return $worksheet;
        }

        throw new PHPExcel_Exception('Workbook does not contain sheet:' . $pValue);
    }

    /**
     * Get sheet names
     *
     * @return string[]
     */
    public static function getSheetNames()
    {
        $returnValue = array();
        $worksheetCount = self::getSheetCount();
        for ($i = 0; $i < $worksheetCount; ++$i) {
            $returnValue[] = self::getSheet($i)->getTitle();
        }

        return $returnValue;
    }

    /**
     * Add external sheet
     *
     * @param  PHPExcel_Worksheet $pSheet External sheet to add
     * @param  int|null $iSheetIndex Index where sheet should go (0,1,..., or null for last)
     * @throws PHPExcel_Exception
     * @return PHPExcel_Worksheet
     */
    public static function addExternalSheet(PHPExcel_Worksheet $pSheet, $iSheetIndex = null) {
        if (self::sheetNameExists($pSheet->getTitle())) {
            throw new PHPExcel_Exception("Workbook already contains a worksheet named '{$pSheet->getTitle()}'. Rename the external sheet first.");
        }

        // count how many cellXfs there are in this workbook currently, we will need this below
        $countCellXfs = count(self::$_cellXfCollection);

        // copy all the shared cellXfs from the external workbook and append them to the current
        foreach ($pSheet->getParent()->getCellXfCollection() as $cellXf) {
            self::addCellXf(clone $cellXf);
        }

        // move sheet to this workbook
        $pSheet->rebindParent(self);

        // update the cellXfs
        foreach ($pSheet->getCellCollection(false) as $cellID) {
            $cell = $pSheet->getCell($cellID);
            $cell->setXfIndex( $cell->getXfIndex() + $countCellXfs );
        }

        return self::addSheet($pSheet, $iSheetIndex);
    }

    /**
     * Get named ranges
     *
     * @return PHPExcel_NamedRange[]
     */
    public static function getNamedRanges() {
        return self::$_namedRanges;
    }

    /**
     * Add named range
     *
     * @param  PHPExcel_NamedRange $namedRange
     * @return PHPExcel
     */
    public static function addNamedRange(PHPExcel_NamedRange $namedRange) {
        if ($namedRange->getScope() == null) {
            // global scope
            self::$_namedRanges[$namedRange->getName()] = $namedRange;
        } else {
            // local scope
            self::$_namedRanges[$namedRange->getScope()->getTitle().'!'.$namedRange->getName()] = $namedRange;
        }
        return true;
    }

    /**
     * Get named range
     *
     * @param  string $namedRange
     * @param  PHPExcel_Worksheet|null $pSheet Scope. Use null for global scope
     * @return PHPExcel_NamedRange|null
     */
    public static function getNamedRange($namedRange, PHPExcel_Worksheet $pSheet = null) {
        $returnValue = null;

        if ($namedRange != '' && ($namedRange !== NULL)) {
            // first look for global defined name
            if (isset(self::$_namedRanges[$namedRange])) {
                $returnValue = self::$_namedRanges[$namedRange];
            }

            // then look for local defined name (has priority over global defined name if both names exist)
            if (($pSheet !== NULL) && isset(self::$_namedRanges[$pSheet->getTitle() . '!' . $namedRange])) {
                $returnValue = self::$_namedRanges[$pSheet->getTitle() . '!' . $namedRange];
            }
        }

        return $returnValue;
    }

    /**
     * Remove named range
     *
     * @param  string  $namedRange
     * @param  PHPExcel_Worksheet|null  $pSheet  Scope: use null for global scope.
     * @return PHPExcel
     */
    public static function removeNamedRange($namedRange, PHPExcel_Worksheet $pSheet = null) {
        if ($pSheet === NULL) {
            if (isset(self::$_namedRanges[$namedRange])) {
                unset(self::$_namedRanges[$namedRange]);
            }
        } else {
            if (isset(self::$_namedRanges[$pSheet->getTitle() . '!' . $namedRange])) {
                unset(self::$_namedRanges[$pSheet->getTitle() . '!' . $namedRange]);
            }
        }
        return self;
    }

    /**
     * Get worksheet iterator
     *
     * @return PHPExcel_WorksheetIterator
     */
    public static function getWorksheetIterator() {
        return new PHPExcel_WorksheetIterator(self);
    }

    /**
     * Copy workbook (!= clone!)
     *
     * @return PHPExcel
     */
    public static function copy() {
        $copied = clone self;

        $worksheetCount = count(self::$_workSheetCollection);
        for ($i = 0; $i < $worksheetCount; ++$i) {
            self::$_workSheetCollection[$i] = self::$_workSheetCollection[$i]->copy();
            self::$_workSheetCollection[$i]->rebindParent(self);
        }

        return $copied;
    }

    /**
     * Implement PHP __clone to create a deep clone, not just a shallow copy.
     */
    public static function __clone() {
        foreach(self as $key => $val) {
            if (is_object($val) || (is_array($val))) {
                self::$key = unserialize(serialize($val));
            }
        }
    }

    /**
     * Get the workbook collection of cellXfs
     *
     * @return PHPExcel_Style[]
     */
    public static function getCellXfCollection()
    {
        return self::$_cellXfCollection;
    }

    /**
     * Get cellXf by index
     *
     * @param  int $pIndex
     * @return PHPExcel_Style
     */
    public static function getCellXfByIndex($pIndex = 0)
    {
        return self::$_cellXfCollection[$pIndex];
    }

    /**
     * Get cellXf by hash code
     *
     * @param  string $pValue
     * @return PHPExcel_Style|false
     */
    public static function getCellXfByHashCode($pValue = '')
    {
        foreach (self::$_cellXfCollection as $cellXf) {
            if ($cellXf->getHashCode() == $pValue) {
                return $cellXf;
            }
        }
        return false;
    }

    /**
     * Check if style exists in style collection
     *
     * @param  PHPExcel_Style $pCellStyle
     * @return boolean
     */
    public static function cellXfExists($pCellStyle = null)
    {
        return in_array($pCellStyle, self::$_cellXfCollection, true);
    }

    /**
     * Get default style
     *
     * @return PHPExcel_Style
     * @throws PHPExcel_Exception
     */
    public static function getDefaultStyle()
    {
        if (isset(self::$_cellXfCollection[0])) {
            return self::$_cellXfCollection[0];
        }
        throw new PHPExcel_Exception('No default style found for this workbook');
    }

    /**
     * Add a cellXf to the workbook
     *
     * @param PHPExcel_Style $style
     */
    public static function addCellXf(PHPExcel_Style $style)
    {
        self::$_cellXfCollection[] = $style;
        $style->setIndex(count(self::$_cellXfCollection) - 1);
    }

    /**
     * Remove cellXf by index. It is ensured that all cells get their xf index updated.
     *
     * @param  int $pIndex Index to cellXf
     * @throws PHPExcel_Exception
     */
    public static function removeCellXfByIndex($pIndex = 0)
    {
        if ($pIndex > count(self::$_cellXfCollection) - 1) {
            throw new PHPExcel_Exception("CellXf index is out of bounds.");
        } else {
            // first remove the cellXf
            array_splice(self::$_cellXfCollection, $pIndex, 1);

            // then update cellXf indexes for cells
            foreach (self::$_workSheetCollection as $worksheet) {
                foreach ($worksheet->getCellCollection(false) as $cellID) {
                    $cell = $worksheet->getCell($cellID);
                    $xfIndex = $cell->getXfIndex();
                    if ($xfIndex > $pIndex ) {
                        // decrease xf index by 1
                        $cell->setXfIndex($xfIndex - 1);
                    } else if ($xfIndex == $pIndex) {
                        // set to default xf index 0
                        $cell->setXfIndex(0);
                    }
                }
            }
        }
    }

    /**
     * Get the cellXf supervisor
     *
     * @return PHPExcel_Style
     */
    public static function getCellXfSupervisor()
    {
        return self::$_cellXfSupervisor;
    }

    /**
     * Get the workbook collection of cellStyleXfs
     *
     * @return PHPExcel_Style[]
     */
    public static function getCellStyleXfCollection()
    {
        return self::$_cellStyleXfCollection;
    }

    /**
     * Get cellStyleXf by index
     *
     * @param  int $pIndex
     * @return PHPExcel_Style
     */
    public static function getCellStyleXfByIndex($pIndex = 0)
    {
        return self::$_cellStyleXfCollection[$pIndex];
    }

    /**
     * Get cellStyleXf by hash code
     *
     * @param  string $pValue
     * @return PHPExcel_Style|false
     */
    public static function getCellStyleXfByHashCode($pValue = '')
    {
        foreach (self::_cellXfStyleCollection as $cellStyleXf) {
            if ($cellStyleXf->getHashCode() == $pValue) {
                return $cellStyleXf;
            }
        }
        return false;
    }

    /**
     * Add a cellStyleXf to the workbook
     *
     * @param PHPExcel_Style $pStyle
     */
    public static function addCellStyleXf(PHPExcel_Style $pStyle)
    {
        self::$_cellStyleXfCollection[] = $pStyle;
        $pStyle->setIndex(count(self::$_cellStyleXfCollection) - 1);
    }

    /**
     * Remove cellStyleXf by index
     *
     * @param int $pIndex
     * @throws PHPExcel_Exception
     */
    public static function removeCellStyleXfByIndex($pIndex = 0)
    {
        if ($pIndex > count(self::$_cellStyleXfCollection) - 1) {
            throw new PHPExcel_Exception("CellStyleXf index is out of bounds.");
        } else {
            array_splice(self::$_cellStyleXfCollection, $pIndex, 1);
        }
    }

    /**
     * Eliminate all unneeded cellXf and afterwards update the xfIndex for all cells
     * and columns in the workbook
     */
    public static function garbageCollect()
    {
        // how many references are there to each cellXf ?
        $countReferencesCellXf = array();
        foreach (self::$_cellXfCollection as $index => $cellXf) {
            $countReferencesCellXf[$index] = 0;
        }

        foreach (self::getWorksheetIterator() as $sheet) {

            // from cells
            foreach ($sheet->getCellCollection(false) as $cellID) {
                $cell = $sheet->getCell($cellID);
                ++$countReferencesCellXf[$cell->getXfIndex()];
            }

            // from row dimensions
            foreach ($sheet->getRowDimensions() as $rowDimension) {
                if ($rowDimension->getXfIndex() !== null) {
                    ++$countReferencesCellXf[$rowDimension->getXfIndex()];
                }
            }

            // from column dimensions
            foreach ($sheet->getColumnDimensions() as $columnDimension) {
                ++$countReferencesCellXf[$columnDimension->getXfIndex()];
            }
        }

        // remove cellXfs without references and create mapping so we can update xfIndex
        // for all cells and columns
        $countNeededCellXfs = 0;
        foreach (self::$_cellXfCollection as $index => $cellXf) {
            if ($countReferencesCellXf[$index] > 0 || $index == 0) { // we must never remove the first cellXf
                ++$countNeededCellXfs;
            } else {
                unset(self::$_cellXfCollection[$index]);
            }
            $map[$index] = $countNeededCellXfs - 1;
        }
        self::$_cellXfCollection = array_values(self::$_cellXfCollection);

        // update the index for all cellXfs
        foreach (self::$_cellXfCollection as $i => $cellXf) {
            $cellXf->setIndex($i);
        }

        // make sure there is always at least one cellXf (there should be)
        if (empty(self::$_cellXfCollection)) {
            self::$_cellXfCollection[] = new PHPExcel_Style();
        }

        // update the xfIndex for all cells, row dimensions, column dimensions
        foreach (self::getWorksheetIterator() as $sheet) {

            // for all cells
            foreach ($sheet->getCellCollection(false) as $cellID) {
                $cell = $sheet->getCell($cellID);
                $cell->setXfIndex( $map[$cell->getXfIndex()] );
            }

            // for all row dimensions
            foreach ($sheet->getRowDimensions() as $rowDimension) {
                if ($rowDimension->getXfIndex() !== null) {
                    $rowDimension->setXfIndex( $map[$rowDimension->getXfIndex()] );
                }
            }

            // for all column dimensions
            foreach ($sheet->getColumnDimensions() as $columnDimension) {
                $columnDimension->setXfIndex( $map[$columnDimension->getXfIndex()] );
            }

			// also do garbage collection for all the sheets
            $sheet->garbageCollect();
        }
    }

    /**
     * Return the unique ID value assigned to this spreadsheet workbook
     *
     * @return string
     */
    public static function getID() {
        return self::$_uniqueID;
    }

}
