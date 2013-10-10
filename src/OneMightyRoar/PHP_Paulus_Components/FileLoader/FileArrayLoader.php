<?php
/**
 * PHP-Paulus-Components
 *
 * Components to enhance Paulus projects to enable quicker, more structured REST API's
 *
 * @copyright   2013 One Mighty Roar
 * @link        http://onemightyroar.com
 */

namespace OneMightyRoar\PHP_Paulus_Components\FileLoader;

use Paulus\FileLoader\AbstractFileLoader;
use SplFileInfo;
use UnexpectedValueException;

/**
 * FileArrayLoader
 *
 * @uses    Paulus\FileLoader\AbstractFileLoader
 * @package OneMightyRoar\PHP_Paulus_Components\FileLoader
 */
class FileArrayLoader extends AbstractFileLoader
{

    /**
     * Constructor
     *
     * @param string $path          The path to traverse
     * @param string $ignore_prefix The prefix of file names to ignore
     * @access public
     */
    public function __construct($path, $ignore_prefix = null)
    {
        // Call our parent
        parent::__construct($path);

        if (null !== $ignore_prefix) {
            $this->setIgnorePrefix($ignore_prefix);
        }
    }

    /**
     * Process the file being loaded
     *
     * @param SplFileInfo $file     The file to process
     * @param mixed $load_return    A reference to the value to be returned by `load()`
     * @access protected
     * @return void
     */
    protected function processFile(SplFileInfo $file, &$load_return)
    {
        // Make sure the return val is an array
        if (empty($load_return)) {
            $load_return = [];
        }

        // Include the file
        $callback = require($file);

        // Verify that we got back a callable callback
        if (!is_callable($callback)) {
            throw new UnexpectedValueException(
                sprintf('Returned value from included file "%s" is not callable', $file->getPathname())
            );
        }

        // Get our name for our array key
        $key_name = $file->getBasename('.'. static::VALID_FILE_EXTENSION);

        $load_return[$key_name] = $callback();
    }
}