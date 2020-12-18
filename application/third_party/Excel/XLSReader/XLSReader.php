<?php

/**
 * PHPExcelReader class
 *
 * @version 1.0.0
 * @author Janson Leung
 */
class XLSReader implements SeekableIterator, Countable
{
    const TYPE_XLS = 'XLS';
    private $index = 0;
    private $handle = array();
    
    /**
     * @param string Path to file
     * @param string Original filename (in case of an uploaded file), used to determine file type, optional
     * @param string MIME type from an upload, used to determine file type, optional
     */
    public function __construct($filePath)
    {
        if (!is_readable($filePath)) {
            throw new Exception('SpreadsheetReader: File (' . $filePath . ') not readable');
        }
        self::Load(self::TYPE_XLS);
        $this->handle = new SpreadsheetReader_XLS($filePath);
    }

    /**
     * Gets information about separate sheets in the given file
     *
     * @return array Associative array where key is sheet index and value is sheet name
     */
    public function Sheets()
    {
        return $this->handle->Sheets();
    }

    /**
     * Changes the current sheet to another from the file.
     * 	Note that changing the sheet will rewind the file to the beginning, even if
     * 	the current sheet index is provided.
     *
     * @param int Sheet index
     *
     * @return bool True if sheet could be changed to the specified one,
     * 	false if not (for example, if incorrect index was provided.
     */
    public function ChangeSheet($index)
    {
        return $this->handle->ChangeSheet($index);
    }

    /**
     * Autoloads the required class for the particular spreadsheet type
     *
     * @param TYPE_* Spreadsheet type, one of TYPE_* constants of this class
     */
    private static function Load($type)
    {
        if ($type != self::TYPE_XLS) {
            throw new Exception('SpreadsheetReader: Invalid type (' . $type . ')');
        }
        
        // 2nd parameter is to prevent autoloading for the class.
        // If autoload works, the require line is unnecessary, if it doesn't, it ends badly.
        if (!class_exists('SpreadsheetReader_' . $type, false)) {
            require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'SpreadsheetReader_' . $type . '.php');
        }
    }
    // !Iterator interface methods

    /**
     * Rewind the Iterator to the first element.
     * Similar to the reset() function for arrays in PHP
     */
    public function rewind()
    {
        $this->index = 0;
        if ($this->handle) {
            $this->handle->rewind();
        }
    }

    /**
     * Return the current element.
     * Similar to the current() function for arrays in PHP
     *
     * @return mixed current element from the collection
     */
    public function current()
    {
        if ($this->handle) {
            return $this->handle->current();
        }
        return null;
    }

    /**
     * Move forward to next element. 
     * Similar to the next() function for arrays in PHP 
     */
    public function next()
    {
        if ($this->handle) {
            $this->index++;
            return $this->handle->next();
        }
        return null;
    }

    /**
     * Return the identifying key of the current element.
     * Similar to the key() function for arrays in PHP
     *
     * @return mixed either an integer or a string
     */
    public function key()
    {
        if ($this->handle) {
            return $this->handle->key();
        }
        return null;
    }

    /**
     * Check if there is a current element after calls to rewind() or next().
     * Used to check if we've iterated to the end of the collection
     *
     * @return boolean FALSE if there's nothing more to iterate over
     */
    public function valid()
    {
        if ($this->handle) {
            return $this->handle->valid();
        }
        return false;
    }

    /**
     * total of file number
     * return int
     */
    public function count()
    {
        if ($this->handle) {
            return $this->handle->count();
        }
        return 0;
    }

    /**
     * Method for SeekableIterator interface. Takes a posiiton and traverses the file to that position
     * The value can be retrieved with a `current()` call afterwards.
     *
     * @param int position in file
     */
    public function seek($position)
    {
        if (!$this->handle) {
            throw new OutOfBoundsException('SpreadsheetReader: No file opened');
        }

        $Currentindex = $this->handle->key();
        if ($Currentindex != $position) {
            if ($position < $Currentindex || is_null($Currentindex) || $position == 0) {
                $this->rewind();
            }

            while ($this->handle->valid() && ($position > $this->handle->key())) {
                $this->handle->next();
            }

            if (!$this->handle->valid()) {
                throw new OutOfBoundsException('SpreadsheetError: position ' . $position . ' not found');
            }
        }

        return null;
    }
}
