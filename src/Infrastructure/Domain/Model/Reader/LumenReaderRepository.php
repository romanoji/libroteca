<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader;

use Illuminate\Support\Facades\DB;
use RJozwiak\Libroteca\Lumen;
use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Domain\Model\Reader\Email;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\ReaderNotFoundException;
use RJozwiak\Libroteca\Domain\Model\Reader\Name;
use RJozwiak\Libroteca\Domain\Model\Reader\Phone;
use RJozwiak\Libroteca\Domain\Model\Reader\Reader;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderRepository;
use RJozwiak\Libroteca\Domain\Model\Reader\Surname;

class LumenReaderRepository implements ReaderRepository
{
    /**
     * @return ReaderID
     */
    public function nextID(): ReaderID
    {
        return new ReaderID(Uuid::uuid4()->toString());
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return Lumen\Models\Reader::count();
    }

    /**
     * @param Reader $reader
     */
    public function save(Reader $reader)
    {
        $data = [
            'name' => (string) $reader->name(),
            'surname' => (string) $reader->surname(),
            'email' => $reader->email()->email(),
            'phone' => $reader->phone()->phone()
        ];

        Lumen\Models\Reader::where('id', $reader->id()->id())
            ->update($data, ['upsert' => true]);
    }

    /**
     * @param ReaderID $id
     * @return Reader
     * @throws ReaderNotFoundException
     */
    public function get(ReaderID $id): Reader
    {
        $data = Lumen\Models\Reader::find($id->id());

        if ($data === null) {
            throw new ReaderNotFoundException();
        }

        return $this->createReader(
            $data['id'], $data['name'], $data['surname'], $data['email'], $data['phone']
        );
    }

    /**
     * @param Email $email
     * @return null|Reader
     */
    public function findOneByEmail(Email $email): ?Reader
    {
        $data = Lumen\Models\Reader::where('email', $email->email())->get();

        if ($data === null) {
            return null;
        }

        return $this->createReader(
            $data['id'], $data['name'], $data['surname'], $data['email'], $data['phone']
        );
    }

    /**
     * @param Phone $phone
     * @return null|Reader
     */
    public function findOneByPhone(Phone $phone): ?Reader
    {
        $data = Lumen\Models\Reader::where('phone', $phone->phone())->get();

        if ($data === null) {
            return null;
        }

        return $this->createReader(
            $data['id'], $data['name'], $data['surname'], $data['email'], $data['phone']
        );
    }

    /**
     * @param int|string $id
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $phone
     * @return Reader
     */
    private function createReader(
        $id,
        string $name,
        string $surname,
        string $email,
        string $phone
    ): Reader {
        return new Reader(
            new ReaderID($id),
            new Name($name),
            new Surname($surname),
            new Email($email),
            new Phone($phone)
        );
    }
}
