<?php
/**
 * @package    quantummanager
 * @author     Dmitry Tsymbal <cymbal@delo-design.ru>
 * @copyright  Copyright © 2020 Delo Design & NorrNext. All rights reserved.
 * @license    GNU General Public License version 3 or later; see license.txt
 * @link       https://www.norrnext.com
 */

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

defined('_JEXEC') or die;

JLoader::import( 'components.com_fields.libraries.fieldsplugin', JPATH_ADMINISTRATOR );
JLoader::register('QuantummanagerHelper', JPATH_SITE . '/administrator/components/com_quantummanager/helpers/quantummanager.php');
JLoader::register('JFormFieldQuantumuploadimage', JPATH_ROOT . '/libraries/lib_fields/fields/quantumuploadimage/quantumuploadimage.php');


/**
 * Class JFormFieldQuantummanagercomfieldsfield
 */
class JFormFieldQuantummanagercomfieldsfield extends JFormFieldQuantumuploadimage
{

	/**
	 * @var string
	 */
	public $type = 'QuantumManagerComFieldsField';


    public function getInput()
    {
        $db = Factory::getDBO();
        $query = $db->getQuery(true)
            ->select($db->quoteName(array('params', 'fieldparams')))
            ->from('#__fields')
            ->where( 'name=' . $db->quote($this->fieldname));
        $field = $db->setQuery( $query )->loadObject();
        $fieldparams = json_decode($field->fieldparams, JSON_OBJECT_AS_ARRAY);

        try
        {
            $this->__set('dropAreaHidden', $this->getAttribute('dropAreaHidden', true));
            $this->__set('copy', $this->getAttribute('copy', true));
            $this->__set('directory', $fieldparams['path']);

            if($this->copy)
            {
                JLoader::register('QuantummanagerLibs', JPATH_SITE . '/administrator/components/com_quantummanager/helpers/quantumlibs.php');

                QuantummanagerLibs::includes([
                    'utils',
                    'notify',
                    'clipboard'
                ]);
            }

            return parent::getInput();
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

}