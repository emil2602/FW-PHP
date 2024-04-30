<?php

namespace Fw\PhpFw\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

class MigrateCommand implements \Fw\PhpFw\Console\ConsoleInterface
{

    private string $name = 'migrate';
    private const MIGRATIONS = 'migrations';

    public function __construct(
        private Connection $connection,
        private string $migrationsPath
    )
    {
    }

    public function execute(array $params = []): int
    {
        try {
            $this->connection->setAutoCommit(false);

            $this->createMigrationTable();

            $appliedMigrations = $this->getAppliedMigrations();

            $migrationsFiles = $this->getMigrationFiles();

            $migrationsToApply = array_values(array_diff($migrationsFiles, $appliedMigrations));

            $schema = new Schema();

            foreach ($migrationsToApply as $migration) {
                $migrationInstance = require $this->migrationsPath."/$migration";
                $migrationInstance->up($schema);

                $this->addMigration($migration);
            }

            $sqlQueries = $schema->toSql($this->connection->getDatabasePlatform());

            foreach ($sqlQueries as $sql) {
                $this->connection->executeQuery($sql);
            }

        } catch (\Throwable $e) {

            throw $e;
        }

        $this->connection->setAutoCommit(true);

        return 0;
    }

    private function createMigrationTable()
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tableExists(self::MIGRATIONS)) {
            $schema = new Schema();
            $table = $schema->createTable(self::MIGRATIONS);
            $table->addColumn('id', Types::INTEGER, [
                'unsigned'=> true,
                'autoincrement'=> true
            ]);

            $table->addColumn('migration', Types::STRING, [
                'length' => 255
            ]);
            $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, [
                'default' => 'CURRENT_TIMESTAMP'
            ]);
            $table->setPrimaryKey(['id']);

            $sqlQueries = $schema->toSql($this->connection->getDatabasePlatform());

            $this->connection->executeQuery($sqlQueries[0]);

            echo 'Migration successful'. PHP_EOL;
        }
    }

    private function getAppliedMigrations()
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        return $queryBuilder
            ->select('migration')
            ->from(self::MIGRATIONS)
            ->executeQuery()
            ->fetchFirstColumn();
    }

    private function getMigrationFiles()
    {
        $migrationFiles = scandir($this->migrationsPath);

        return array_values(
            array_filter($migrationFiles, function ($fileName) {
                return !in_array($fileName, ['.', '..']);
            })
        );
    }

    private function addMigration(string $migration): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->insert(self::MIGRATIONS)
                     ->values(['migration' => ':migration'])
                     ->setParameter('migration', $migration)
                     ->executeQuery();
    }
}