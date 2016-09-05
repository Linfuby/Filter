<?php
namespace Meling\Filter\Sexes;

/**
 * Class Categories
 * @package Meling\Filter\Sexes
 * @since   2.0
 */
class Categories
{
    /** @var \Meling\Filter */
    protected $filter;

    /** @var \PHPixie\Slice\Type\ArrayData\Slice */
    protected $data;

    /** @var Sex */
    protected $sex;

    /** @var Categories\Category[] */
    private $categories;

    /**
     * categories constructor.
     * @param \Meling\Filter                      $filter
     * @param Sex                                 $sex
     * @param \PHPixie\Slice\Type\ArrayData\Slice $data
     * @since 2.0
     */
    public function __construct(\Meling\Filter $filter, Sex $sex, \PHPixie\Slice\Type\ArrayData\Slice $data)
    {
        $this->filter = $filter;
        $this->sex    = $sex;
        $this->data   = $data;
    }

    /**
     * @return Categories\Category[]
     * @since 2.0
     */
    public function asArray()
    {
        $this->requireCategories();

        return $this->categories;
    }

    /**
     * @param mixed $id
     * @return Categories\Category
     * @throws \Exception
     * @since 2.0
     */
    public function get($id)
    {
        $this->requireCategories();
        if(array_key_exists($id, $this->categories)) {
            return $this->categories[$id];
        }
        throw new \Exception();
    }

    /**
     * @return array
     * @since 2.0
     */
    public function selected()
    {
        return $this->data->keys();
    }

    /**
     * @since 2.0
     */
    protected function requireCategories()
    {
        if($this->categories !== null) {
            return;
        }
        $query = $this->filter->connection()->selectQuery();
        $query->fields(
            array(
                'categories.id',
                'categories.name',
            )
        );
        $query->table('categories');
        $query->join(strtolower('productTypes'), 'productTypes');
        $query->on('productTypes.categoryId', 'categories.id');
        $query->join('products');
        $query->on('products.productTypeId', 'productTypes.id');
        // Только с остатками
        $query->join('restOptions', 'restProducts');
        $query->on('restProducts.productId', 'products.id');

        $query->join('options');
        $query->on('options.id', 'restProducts.optionId');
        // Только в доступных магазинах
        $query->join('shops');
        $query->on('shops.id', 'restProducts.shopId');
        $query->where('shops.publish', 1);
        $query->where('shops.active', 1);
        $query->where('shops.hidden', 0);
        // Только с изображениями
        $query->join('productImages');
        $query->on('productImages.productId', 'products.id');
        if($this->sex->id() !== 3003) {
            $query->where('categories.sexId', $this->sex->id());
        }
        $query->where('categories.depth', 1);
        $query->where('categories.publish', 1);
        $query->orderAscendingBy('categories.name');
        $query->groupBy('categories.id');
        /** @var \PHPixie\Database\Result $result */
        $result     = $query->execute();
        $categories = [];
        foreach($result->asArray() as $category) {
            $categories[$category->id] = new Categories\Category($this->filter, $this->sex, $this->data->arraySlice($category->id), $category->id, $category->name, in_array($category->id, $this->selected()));
        }
        $this->categories = $categories;

    }

}

