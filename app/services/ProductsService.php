<?php

namespace App\Services;

use App\Exceptions\HttpExceptions\Http404Exception;
use App\Exceptions\ServiceException;
use App\Models\Products;
use App\Validation\ProductValidation;
use App\Validation\SignupValidation;


class ProductsService extends AbstractService
{
    public function listProducts()
    {
        $sql = "SELECT * FROM `products` ORDER BY id";
        $state = $this->db->prepare($sql);
        $state->execute();
        $data = $state->fetchAll();
        return $data;
    }


    public function create(array $data)
    {
        try {
            $data['item_name'] = $data['itemName'];
            $data['user_id'] = $data['userId'];

            $product = new Products();
            $product->assign($data); //table data
            $result = $product->create(); // create

            if (!$result) {
                throw new ServiceException(
                    'Unable to create product',
                    self::ERROR_UNABLE_CREATE_USER
                );
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return [
            'productId' => $product->id
        ];

    }

    /**
     * Product details
     *
     * @param int $id
     * @return array
     */
    public function details(int $id)
    {
        $data = [];
        try {
            $data['item_name'] = $data['itemName'];
            $data['user_id'] = $data['userId'];

            $sql = "SELECT * FROM products WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('id', $id);
            $stmt->execute();
            $details = $stmt->fetchAll();

            if (!$details) {
                throw new ServiceException(
                    'Product not found',
                    self::ERROR_PRODUCT_NOT_FOUND
                );
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return $details;

    }

    public function update(array $data)
    {
        try {
            $data['item_name'] = $data['itemName'];
            $data['user_id'] = $data['userId'];

            $sql = "SELECT * FROM products WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('id', $id);
            $stmt->execute();
            $table_id = $stmt->fetchAll();
            if (!$table_id) {
                throw new ServiceException(
                    'Product not found',
                    self::ERROR_PRODUCT_NOT_FOUND
                );
            }

            // True
            $sql = "UPDATE products 
                    Set price = :price 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('id', $table_id);
            $stmt->bindParam('price', $price);
            $reslut = $stmt->execute();
            if (!$reslut) {
                throw new ServiceException(
                    'Unable to update product',
                    self::ERROR_UNABLE_UPDATE_PRODUCT
                );
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return null;
    }


    public function delete(int $id)
    {
        try {
            $sql = "SELECT id FROM products WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('id', $id);
            $stmt->execute();
            $details = $stmt->fetch();

            if (!$details) {
                throw new ServiceException(
                    'Product not found',
                    self::ERROR_PRODUCT_NOT_FOUND
                );
            }
            // True
            $sql = "DELETE FROM products 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('id', $id);
            $reslut = $stmt->execute();

            if (!$reslut) {
                throw new ServiceException(
                    'Product not delete!',
                    self::ERROR_PRODUCT_NOT_Delete
                );
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return null;
    }


}