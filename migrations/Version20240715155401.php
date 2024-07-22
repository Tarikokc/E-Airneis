<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240715155401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, uilisateur INT NOT NULL, produit INT NOT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY cart_ibfk_2');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY cart_ibfk_1');
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY payments_ibfk_1');
        $this->addSql('ALTER TABLE promotions DROP FOREIGN KEY promotions_ibfk_1');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY reviews_ibfk_1');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY reviews_ibfk_2');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE designers');
        $this->addSql('DROP TABLE payments');
        $this->addSql('DROP TABLE promotions');
        $this->addSql('DROP TABLE reviews');
        $this->addSql('ALTER TABLE orderdetails DROP FOREIGN KEY orderdetails_ibfk_2');
        $this->addSql('ALTER TABLE orderdetails DROP FOREIGN KEY orderdetails_ibfk_1');
        $this->addSql('ALTER TABLE orderdetails DROP FOREIGN KEY orderdetails_ibfk_2');
        $this->addSql('ALTER TABLE orderdetails DROP FOREIGN KEY orderdetails_ibfk_1');
        $this->addSql('ALTER TABLE orderdetails CHANGE order_detail_id order_detail_id INT AUTO_INCREMENT NOT NULL, CHANGE order_id order_id INT NOT NULL, CHANGE product_id product_id INT NOT NULL, CHANGE quantity quantity INT NOT NULL, CHANGE unit_price unit_price NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE orderdetails ADD CONSTRAINT FK_489AFCDC8D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (order_id)');
        $this->addSql('ALTER TABLE orderdetails ADD CONSTRAINT FK_489AFCDC4584665A FOREIGN KEY (product_id) REFERENCES products (product_id)');
        $this->addSql('DROP INDEX order_id ON orderdetails');
        $this->addSql('CREATE INDEX IDX_489AFCDC8D9F6D38 ON orderdetails (order_id)');
        $this->addSql('DROP INDEX product_id ON orderdetails');
        $this->addSql('CREATE INDEX IDX_489AFCDC4584665A ON orderdetails (product_id)');
        $this->addSql('ALTER TABLE orderdetails ADD CONSTRAINT orderdetails_ibfk_2 FOREIGN KEY (product_id) REFERENCES products (product_id)');
        $this->addSql('ALTER TABLE orderdetails ADD CONSTRAINT orderdetails_ibfk_1 FOREIGN KEY (order_id) REFERENCES orders (order_id)');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY orders_ibfk_1');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY orders_ibfk_1');
        $this->addSql('ALTER TABLE orders CHANGE order_id order_id INT AUTO_INCREMENT NOT NULL, CHANGE user_id user_id INT NOT NULL, CHANGE order_date order_date DATE NOT NULL, CHANGE total_amount total_amount NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES `users` (user_id)');
        $this->addSql('DROP INDEX user_id ON orders');
        $this->addSql('CREATE INDEX IDX_E52FFDEEA76ED395 ON orders (user_id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT orders_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (user_id)');
        $this->addSql('ALTER TABLE productcategories CHANGE category_id category_id INT AUTO_INCREMENT NOT NULL, CHANGE category_name category_name VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE productphotos DROP FOREIGN KEY productphotos_ibfk_1');
        $this->addSql('ALTER TABLE productphotos DROP FOREIGN KEY productphotos_ibfk_1');
        $this->addSql('ALTER TABLE productphotos CHANGE photo_url photo_url VARCHAR(255) NOT NULL, CHANGE is_primary is_primary TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE productphotos ADD CONSTRAINT FK_231277194584665A FOREIGN KEY (product_id) REFERENCES products (product_id)');
        $this->addSql('DROP INDEX product_id ON productphotos');
        $this->addSql('CREATE INDEX IDX_231277194584665A ON productphotos (product_id)');
        $this->addSql('ALTER TABLE productphotos ADD CONSTRAINT productphotos_ibfk_1 FOREIGN KEY (product_id) REFERENCES products (product_id)');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY products_ibfk_1');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY products_ibfk_2');
        $this->addSql('DROP INDEX designer_id ON products');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY products_ibfk_1');
        $this->addSql('ALTER TABLE products DROP designer_id, CHANGE name name VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE price price INT NOT NULL, CHANGE stock_quantity stock_quantity INT NOT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A12469DE2 FOREIGN KEY (category_id) REFERENCES productcategories (category_id)');
        $this->addSql('DROP INDEX category_id ON products');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A12469DE2 ON products (category_id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT products_ibfk_1 FOREIGN KEY (category_id) REFERENCES productcategories (category_id)');
        $this->addSql('ALTER TABLE product_materials DROP FOREIGN KEY product_materials_ibfk_1');
        $this->addSql('ALTER TABLE product_materials DROP FOREIGN KEY product_materials_ibfk_2');
        $this->addSql('ALTER TABLE product_materials DROP FOREIGN KEY product_materials_ibfk_2');
        $this->addSql('ALTER TABLE product_materials ADD CONSTRAINT FK_F5B7A0384584665A FOREIGN KEY (product_id) REFERENCES products (product_id)');
        $this->addSql('ALTER TABLE product_materials ADD CONSTRAINT FK_F5B7A038E308AC6F FOREIGN KEY (material_id) REFERENCES materials (material_id)');
        $this->addSql('DROP INDEX material_id ON product_materials');
        $this->addSql('CREATE INDEX IDX_F5B7A038E308AC6F ON product_materials (material_id)');
        $this->addSql('ALTER TABLE product_materials ADD CONSTRAINT product_materials_ibfk_2 FOREIGN KEY (material_id) REFERENCES materials (material_id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX email ON users');
        $this->addSql('ALTER TABLE users CHANGE registration_date registration_date VARCHAR(255) DEFAULT NULL, CHANGE token token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (cart_id INT NOT NULL, user_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT DEFAULT NULL, INDEX user_id (user_id), INDEX product_id (product_id), PRIMARY KEY(cart_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE designers (designer_id INT NOT NULL, designer_name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(designer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE payments (payment_id INT NOT NULL, order_id INT DEFAULT NULL, payment_date DATE DEFAULT NULL, payment_method VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, amount NUMERIC(10, 2) DEFAULT NULL, INDEX order_id (order_id), PRIMARY KEY(payment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE promotions (promotion_id INT NOT NULL, product_id INT DEFAULT NULL, discount_percentage INT DEFAULT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, INDEX product_id (product_id), PRIMARY KEY(promotion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reviews (review_id INT NOT NULL, product_id INT DEFAULT NULL, user_id INT DEFAULT NULL, rating INT DEFAULT NULL, comment TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, review_date DATE DEFAULT NULL, INDEX product_id (product_id), INDEX user_id (user_id), PRIMARY KEY(review_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT cart_ibfk_2 FOREIGN KEY (product_id) REFERENCES products (product_id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT cart_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (user_id)');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT payments_ibfk_1 FOREIGN KEY (order_id) REFERENCES orders (order_id)');
        $this->addSql('ALTER TABLE promotions ADD CONSTRAINT promotions_ibfk_1 FOREIGN KEY (product_id) REFERENCES products (product_id)');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT reviews_ibfk_1 FOREIGN KEY (product_id) REFERENCES products (product_id)');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT reviews_ibfk_2 FOREIGN KEY (user_id) REFERENCES users (user_id)');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE orderdetails DROP FOREIGN KEY FK_489AFCDC8D9F6D38');
        $this->addSql('ALTER TABLE orderdetails DROP FOREIGN KEY FK_489AFCDC4584665A');
        $this->addSql('ALTER TABLE orderdetails DROP FOREIGN KEY FK_489AFCDC8D9F6D38');
        $this->addSql('ALTER TABLE orderdetails DROP FOREIGN KEY FK_489AFCDC4584665A');
        $this->addSql('ALTER TABLE orderdetails CHANGE order_detail_id order_detail_id INT NOT NULL, CHANGE quantity quantity INT DEFAULT NULL, CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT NULL, CHANGE order_id order_id INT DEFAULT NULL, CHANGE product_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orderdetails ADD CONSTRAINT orderdetails_ibfk_2 FOREIGN KEY (product_id) REFERENCES products (product_id)');
        $this->addSql('ALTER TABLE orderdetails ADD CONSTRAINT orderdetails_ibfk_1 FOREIGN KEY (order_id) REFERENCES orders (order_id)');
        $this->addSql('DROP INDEX idx_489afcdc4584665a ON orderdetails');
        $this->addSql('CREATE INDEX product_id ON orderdetails (product_id)');
        $this->addSql('DROP INDEX idx_489afcdc8d9f6d38 ON orderdetails');
        $this->addSql('CREATE INDEX order_id ON orderdetails (order_id)');
        $this->addSql('ALTER TABLE orderdetails ADD CONSTRAINT FK_489AFCDC8D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (order_id)');
        $this->addSql('ALTER TABLE orderdetails ADD CONSTRAINT FK_489AFCDC4584665A FOREIGN KEY (product_id) REFERENCES products (product_id)');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEA76ED395');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEA76ED395');
        $this->addSql('ALTER TABLE orders CHANGE order_id order_id INT NOT NULL, CHANGE order_date order_date DATE DEFAULT NULL, CHANGE total_amount total_amount NUMERIC(10, 2) DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT orders_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (user_id)');
        $this->addSql('DROP INDEX idx_e52ffdeea76ed395 ON orders');
        $this->addSql('CREATE INDEX user_id ON orders (user_id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES `users` (user_id)');
        $this->addSql('ALTER TABLE productcategories CHANGE category_id category_id INT NOT NULL, CHANGE category_name category_name VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE productphotos DROP FOREIGN KEY FK_231277194584665A');
        $this->addSql('ALTER TABLE productphotos DROP FOREIGN KEY FK_231277194584665A');
        $this->addSql('ALTER TABLE productphotos CHANGE photo_url photo_url VARCHAR(255) DEFAULT NULL, CHANGE is_primary is_primary TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE productphotos ADD CONSTRAINT productphotos_ibfk_1 FOREIGN KEY (product_id) REFERENCES products (product_id)');
        $this->addSql('DROP INDEX idx_231277194584665a ON productphotos');
        $this->addSql('CREATE INDEX product_id ON productphotos (product_id)');
        $this->addSql('ALTER TABLE productphotos ADD CONSTRAINT FK_231277194584665A FOREIGN KEY (product_id) REFERENCES products (product_id)');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A12469DE2');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A12469DE2');
        $this->addSql('ALTER TABLE products ADD designer_id INT DEFAULT NULL, CHANGE name name VARCHAR(100) DEFAULT NULL, CHANGE description description TEXT DEFAULT NULL, CHANGE price price NUMERIC(10, 2) DEFAULT NULL, CHANGE stock_quantity stock_quantity INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT products_ibfk_1 FOREIGN KEY (category_id) REFERENCES productcategories (category_id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT products_ibfk_2 FOREIGN KEY (designer_id) REFERENCES designers (designer_id)');
        $this->addSql('CREATE INDEX designer_id ON products (designer_id)');
        $this->addSql('DROP INDEX idx_b3ba5a5a12469de2 ON products');
        $this->addSql('CREATE INDEX category_id ON products (category_id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A12469DE2 FOREIGN KEY (category_id) REFERENCES productcategories (category_id)');
        $this->addSql('ALTER TABLE product_materials DROP FOREIGN KEY FK_F5B7A0384584665A');
        $this->addSql('ALTER TABLE product_materials DROP FOREIGN KEY FK_F5B7A038E308AC6F');
        $this->addSql('ALTER TABLE product_materials DROP FOREIGN KEY FK_F5B7A038E308AC6F');
        $this->addSql('ALTER TABLE product_materials ADD CONSTRAINT product_materials_ibfk_1 FOREIGN KEY (product_id) REFERENCES products (product_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_materials ADD CONSTRAINT product_materials_ibfk_2 FOREIGN KEY (material_id) REFERENCES materials (material_id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_f5b7a038e308ac6f ON product_materials');
        $this->addSql('CREATE INDEX material_id ON product_materials (material_id)');
        $this->addSql('ALTER TABLE product_materials ADD CONSTRAINT FK_F5B7A038E308AC6F FOREIGN KEY (material_id) REFERENCES materials (material_id)');
        $this->addSql('ALTER TABLE `users` CHANGE registration_date registration_date DATE DEFAULT NULL, CHANGE token token VARCHAR(250) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX email ON `users` (email)');
    }
}
