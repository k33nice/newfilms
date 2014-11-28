<?php

namespace Service\Products;

class Index extends \Service\Base {

    public function validate($params) {
        $rules = [
            'Search' => [ 'max_length' => 100 ]
        ];

        return \Service\Validator::validate($params, $rules);
    }

    public function execute($params) {
        $searchFields = ['SearchFieldRu'];

        $products = \Engine\ProductsQuery::create()
            ->filterByInStock(0, \Propel\Runtime\ActiveQuery\Criteria::GREATER_THAN)
            ->filterByIsArchive(0, \Propel\Runtime\ActiveQuery\Criteria::EQUAL)
            ->searchByFields( $searchFields, $params['Search'] )
            ->select(array('NameRu', 'ProductCode', 'Price', 'PriceBN', 'Points', 'ProductCode', 'ProductID', 'Weight', 'Overall'))
            ->joinWithProductPictures()
                ->withColumn('ProductPictures.Thumbnail',  'Thumbnail')
            ->limit(10)
            ->find();

        $result = array();
        foreach ($products as $product) {
            $product['ImgUrl'] = $this->getImageUrl($product);
            array_push($result, $product);
        }

        return [ 'status' => 1, 'products' => $result ];
    }
}

?>