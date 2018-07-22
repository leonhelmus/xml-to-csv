<?php
/**
 * Class for storing all categories
 *
 * User: leonhelmus
 * Date: 18-7-2018
 * Time: 14:00
 */

namespace ContactImporter\Source;


use SimpleXMLElement;

class Categories
{
    /**
     * @var array
     */
    protected $categories = [];

    /**
     * Get all categories
     *
     * @return array
     */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * Set all categories these will be aligned to the interests of the customer
     *
     * @param SimpleXMLElement $categories
     * @return bool
     */
    public function setCategories($categories) {
        if(!$this->categories) {
            foreach ($categories->category as $category) {
                /**
                 * @var $category SimpleXMLElement
                 */
                $id = (string)$category->attributes()->id[0];
                $name = (string)$category->name;
                if($id && $name) {
                    $this->categories[$id] = $name;
                }
            }
        }
        return true;
    }

    /**
     * Get category by id
     *
     * @param string $categoryId
     * @return string
     */
    public function getCategory($categoryId) {
        if($this->categories) {
            return $this->categories[$categoryId];
        }
        return null;
    }
}