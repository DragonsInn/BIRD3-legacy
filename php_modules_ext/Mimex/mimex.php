<?php

/**
 * Mimex
 *
 * Simple class for converting extension to mimetypes and vice versa.
 * It also detects file mimetypes using PHP Fileinfo extension.
 * This code was inspired by http://goo.gl/KsTLx
 *
 * @package	Mimex
 * @author  Jose' Pedro Saraiva <nocive at gmail.com>
 */
if (!defined('MIMEX_MAP')) {
    define('MIMEX_MAP', __DIR__ . DIRECTORY_SEPARATOR . 'mime.types');
}

class Mimex
{

    /**
     * Filename which contains mimetypes to extensions mapping
     *
     * @var string
     * @access protected
     */
    protected static $_mimetypesMap = MIMEX_MAP;

    /**
     * Holds Fileinfo class instance
     *
     * @var Fileinfo
     * @access protected
     */
    protected static $_finfo;

    /**
     * Retrieves the extension for a given filename
     *
     * @param string $file
     * @param bool $realDetect
     * @return string
     */
    public static function extension($file, $realDetect = true)
    {
        return $realDetect ? static::mimetypeToExtension(static::detectMimetype($file)) : strtolower(pathinfo($file, PATHINFO_EXTENSION));
    } // extension }}}
    
    /**
     * Retrieves the mimetype for a given filenmae
     *
     * @param string $file
     * @param bool $realDetect
     * @return string
     */
    public static function mimetype($file, $realDetect = true)
    {
        return $realDetect ? static::detectMimetype($file) : static::extensionToMimetype(static::extension($file));
    } // mimetype }}}
    
    /**
     * Attempts to detect file mimetype using Fileinfo extension
     *
     * @param string $file
     * @return string
     */
    public static function detectMimetype($file)
    {
        $mimetype = static::_getFileinfo()->file($file);
        // fix erroneous mimetype for favicons returned by some versions of fileinfo
        $mimetype = str_replace('image/x-ico', 'image/x-icon', $mimetype);
        return $mimetype;
    } // detectMimetype }}}
    
    /**
     * Retrieves the mimetype for a given extension
     *
     * @param string $ext
     * @return string
     */
    public static function extensionToMimetype($ext)
    {
        static $types;
        
        if (!isset($types)) {
            $types = static::extensionsMimetypes();
        }
        $ext = strtolower($ext);
        return isset($types[$ext]) ? $types[$ext] : null;
    } // extensionToMimetype }}}
    
    /**
     * Retrieves the extension for a given mimetype
     *
     * @param string $type
     * @return string
     */
    public static function mimetypeToExtension($type)
    {
        static $exts;
        
        if (!isset($exts)) {
            $exts = static::mimetypesExtensions();
        }
        $extension = isset($exts[$type]) ? $exts[$type] : null;
        // prefer jpg over jpeg
        $extension = str_replace('jpeg', 'jpg', $extension);
        return $extension;
    } // mimetypeToExtension }}}
    
    /**
     * Returns the array map of mimetypes to extensions
     *
     * @return array
     */
    public static function mimetypesExtensions()
    {
        $mimeMap = static::_getMapFilename();
        $file = fopen($mimeMap, 'r');
        // Returns the system MIME type mapping of MIME types to extensions,
        // as defined in /etc/mime.types (considering the first extension listed to be canonical).
        $out = array();
        while (($line = fgets($file)) !== false) {
            $line = trim(preg_replace(array(
                    '@^\s*#.*$@',
                    '@^\s*$@'
            ), '', $line));
            if (!$line) {
                continue;
            }
            $parts = preg_split('@\s+@', $line);
            if (count($parts) <= 1) {
                continue;
            }
            $type = array_shift($parts);
            if (!isset($out[$type])) {
                $out[$type] = array_shift($parts);
            }
        }
        fclose($file);
        return $out;
    } // mimetypesExtensions }}}
    
    /**
     * Returns the array map of extensions to mimetypes
     *
     * @return array
     */
    public static function extensionsMimetypes()
    {
        $mimeMap = static::_getMapFilename();
        $file = fopen($mimeMap, 'r');
        // Returns the system MIME type mapping of extensions to MIME types, as defined in /etc/mime.types.
        $out = array();
        while (($line = fgets($file)) !== false) {
            $line = trim(preg_replace(array(
                    '@^\s*#.*$@',
                    '@^\s*$@'
            ), '', $line));
            if (!$line) {
                continue;
            }
            $parts = preg_split('@\s+@', $line);
            if (count($parts) <= 1) {
                continue;
            }
            $type = array_shift($parts);
            foreach ($parts as $p) {
                $out[$p] = $type;
            }
        }
        fclose($file);
        return $out;
    } // extensionsMimetypes }}}
    
    /**
     * Retrieves the mimetypes <-> extensions mapping filename
     *
     * @throws Exception
     * @return array
     */
    protected static function _getMapFilename()
    {
        static $checked = false;
        
        if (!$checked) {
            if (!is_file(static::$_mimetypesMap)) {
                throw new Exception("System mimetypes map not found '" . static::$_mimetypesMap . "'");
            }
            if (!is_readable(static::$_mimetypesMap)) {
                throw new Exception("System mimetypes map not readable '" . static::$_mimetypesMap . "'");
            }
            $checked = true;
        }
        
        return static::$_mimetypesMap;
    } // _getMapFilename }}}
    
    /**
     * Returns the Fileinfo class instance
     *
     * @throws Exception
     * @return Fileinfo
     */
    protected static function _getFileinfo()
    {
        if (!static::$_finfo) {
            if (!extension_loaded('fileinfo')) {
                throw new Exception('Can\'t detect mimetype, Fileinfo extension not loaded');
            }
            static::$_finfo = new finfo(FILEINFO_MIME_TYPE);
        }
        return static::$_finfo;
    }
}
