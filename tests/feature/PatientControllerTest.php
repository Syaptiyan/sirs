<?php

namespace Tests\Feature;

use App\Models\PatientModel;
use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class PatientControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    private PatientModel $patientModel;
    private UserModel $userModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->patientModel = new PatientModel();
        $this->userModel = new UserModel();
    }

    private function createPatient(array $overrides = []): object
    {
        $data = array_merge([
            'name' => 'John Doe',
            'gender' => 'M',
            'birth_date' => '1990-01-15',
            'phone' => '08123456789',
            'email' => 'john@example.com',
            'is_active' => true,
        ], $overrides);

        $id = $this->patientModel->insert($data);
        return $this->patientModel->find($id);
    }

    private function loginAsAdmin(): void
    {
        $userId = $this->userModel->insert([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT, ['cost' => 12]),
            'is_active' => true,
        ]);

        session()->set([
            'logged_in' => true,
            'user_id' => $userId,
            'user_name' => 'Admin User',
            'user_email' => 'admin@example.com',
            'roles' => ['admin'],
            'permissions' => ['view_patients', 'create_patients', 'edit_patients', 'delete_patients'],
            'last_activity' => time(),
        ]);
    }

    public function testIndexPageRenders(): void
    {
        $this->loginAsAdmin();

        $result = $this->get('/patients');

        $result->assertStatus(200);
    }

    public function testIndexReturnsJsonForApiRequest(): void
    {
        $this->loginAsAdmin();
        $this->createPatient(['name' => 'API Patient']);

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/patients');

        $result->assertStatus(200);
        $result->assertJSONFragment(['success' => true]);
    }

    public function testIndexWithSearchFilter(): void
    {
        $this->loginAsAdmin();
        $this->createPatient(['name' => 'Ahmad Fauzi']);
        $this->createPatient(['name' => 'Budi Santoso']);

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/patients?search=Ahmad');

        $result->assertStatus(200);
        $json = $result->getJSON();
        $this->assertTrue($json['success']);
    }

    public function testIndexWithGenderFilter(): void
    {
        $this->loginAsAdmin();
        $this->createPatient(['name' => 'Male', 'gender' => 'M']);
        $this->createPatient(['name' => 'Female', 'gender' => 'F']);

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/patients?gender=F');

        $result->assertStatus(200);
        $json = $result->getJSON();
        $this->assertTrue($json['success']);
    }

    public function testIndexWithPagination(): void
    {
        $this->loginAsAdmin();
        for ($i = 1; $i <= 5; $i++) {
            $this->createPatient(['name' => "Patient {$i}"]);
        }

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/patients?page=1&per_page=2');

        $result->assertStatus(200);
        $json = $result->getJSON();
        $this->assertEquals(1, $json['meta']['page']);
        $this->assertEquals(2, $json['meta']['per_page']);
    }

    public function testCreatePageRenders(): void
    {
        $this->loginAsAdmin();

        $result = $this->get('/patients/create');

        $result->assertStatus(200);
    }

    public function testStoreSuccess(): void
    {
        $this->loginAsAdmin();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/patients', [
            'name' => 'New Patient',
            'gender' => 'F',
            'birth_date' => '1995-05-20',
            'phone' => '08123456789',
            'email' => 'newpatient@example.com',
        ]);

        $result->assertStatus(201);
        $result->assertJSONFragment(['success' => true]);

        $patient = $this->patientModel->where('name', 'New Patient')->first();
        $this->assertNotNull($patient);
        $this->assertNotNull($patient->uuid);
        $this->assertNotNull($patient->mr_number);
    }

    public function testStoreValidationFails(): void
    {
        $this->loginAsAdmin();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/patients', [
            'name' => '',
            'gender' => 'X',
            'birth_date' => 'invalid-date',
        ]);

        $result->assertStatus(422);
        $result->assertJSONFragment(['success' => false]);
    }

    public function testStoreValidationRequiresName(): void
    {
        $this->loginAsAdmin();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/patients', [
            'gender' => 'M',
            'birth_date' => '1990-01-01',
        ]);

        $result->assertStatus(422);
    }

    public function testStoreValidationRequiresGender(): void
    {
        $this->loginAsAdmin();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/patients', [
            'name' => 'Test Patient',
            'birth_date' => '1990-01-01',
        ]);

        $result->assertStatus(422);
    }

    public function testStoreValidationRequiresValidGender(): void
    {
        $this->loginAsAdmin();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/patients', [
            'name' => 'Test Patient',
            'gender' => 'X',
            'birth_date' => '1990-01-01',
        ]);

        $result->assertStatus(422);
    }

    public function testShowReturnsPatient(): void
    {
        $this->loginAsAdmin();
        $patient = $this->createPatient();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/patients/' . $patient->uuid);

        $result->assertStatus(200);
        $result->assertJSONFragment(['success' => true]);
        $result->assertJSONFragment(['name' => $patient->name]);
    }

    public function testShowReturns404ForNonExistent(): void
    {
        $this->loginAsAdmin();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/patients/non-existent-uuid');

        $result->assertStatus(404);
        $result->assertJSONFragment(['success' => false]);
    }

    public function testEditPageRenders(): void
    {
        $this->loginAsAdmin();
        $patient = $this->createPatient();

        $result = $this->get('/patients/' . $patient->uuid . '/edit');

        $result->assertStatus(200);
    }

    public function testUpdateSuccess(): void
    {
        $this->loginAsAdmin();
        $patient = $this->createPatient();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put('/patients/' . $patient->uuid, [
            'name' => 'Updated Name',
            'gender' => 'M',
            'birth_date' => '1990-01-15',
            'phone' => '08987654321',
        ]);

        $result->assertStatus(200);
        $result->assertJSONFragment(['success' => true]);

        $updated = $this->patientModel->find($patient->id);
        $this->assertEquals('Updated Name', $updated->name);
        $this->assertEquals('08987654321', $updated->phone);
    }

    public function testUpdateValidationFails(): void
    {
        $this->loginAsAdmin();
        $patient = $this->createPatient();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put('/patients/' . $patient->uuid, [
            'name' => '',
            'gender' => 'X',
        ]);

        $result->assertStatus(422);
        $result->assertJSONFragment(['success' => false]);
    }

    public function testUpdateNonExistentPatient(): void
    {
        $this->loginAsAdmin();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put('/patients/non-existent-uuid', [
            'name' => 'Updated Name',
            'gender' => 'M',
            'birth_date' => '1990-01-15',
        ]);

        $result->assertStatus(500);
        $result->assertJSONFragment(['success' => false]);
    }

    public function testDeleteSuccess(): void
    {
        $this->loginAsAdmin();
        $patient = $this->createPatient();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete('/patients/' . $patient->uuid);

        $result->assertStatus(200);
        $result->assertJSONFragment(['success' => true]);

        $deleted = $this->patientModel->find($patient->id);
        $this->assertNull($deleted);
    }

    public function testDeleteNonExistentPatient(): void
    {
        $this->loginAsAdmin();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete('/patients/non-existent-uuid');

        $result->assertStatus(500);
        $result->assertJSONFragment(['success' => false]);
    }

    public function testVisitsEndpoint(): void
    {
        $this->loginAsAdmin();
        $patient = $this->createPatient();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/patients/' . $patient->uuid . '/visits');

        $result->assertStatus(200);
        $result->assertJSONFragment(['success' => true]);
    }

    public function testAllergiesEndpoint(): void
    {
        $this->loginAsAdmin();
        $patient = $this->createPatient();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/patients/' . $patient->uuid . '/allergies');

        $result->assertStatus(200);
        $result->assertJSONFragment(['success' => true]);
    }

    public function testStoreAllergySuccess(): void
    {
        $this->loginAsAdmin();
        $patient = $this->createPatient();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/patients/' . $patient->uuid . '/allergies', [
            'allergy_name' => 'Penisilin',
            'severity' => 'severe',
            'reaction' => 'Anafilaksis',
        ]);

        $result->assertStatus(201);
        $result->assertJSONFragment(['success' => true]);
    }

    public function testStoreAllergyValidationFails(): void
    {
        $this->loginAsAdmin();
        $patient = $this->createPatient();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/patients/' . $patient->uuid . '/allergies', [
            'allergy_name' => '',
            'severity' => 'invalid',
        ]);

        $result->assertStatus(422);
        $result->assertJSONFragment(['success' => false]);
    }

    public function testChronicDiseasesEndpoint(): void
    {
        $this->loginAsAdmin();
        $patient = $this->createPatient();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/patients/' . $patient->uuid . '/chronic-diseases');

        $result->assertStatus(200);
        $result->assertJSONFragment(['success' => true]);
    }

    public function testStoreChronicDiseaseSuccess(): void
    {
        $this->loginAsAdmin();
        $patient = $this->createPatient();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/patients/' . $patient->uuid . '/chronic-diseases', [
            'disease_name' => 'Diabetes Melitus Tipe 2',
            'diagnosed_date' => '2020-06-15',
            'treatment' => 'Metformin 500mg',
        ]);

        $result->assertStatus(201);
        $result->assertJSONFragment(['success' => true]);
    }

    public function testDocumentsEndpoint(): void
    {
        $this->loginAsAdmin();
        $patient = $this->createPatient();

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/patients/' . $patient->uuid . '/documents');

        $result->assertStatus(200);
        $result->assertJSONFragment(['success' => true]);
    }

    public function testExportEndpoint(): void
    {
        $this->loginAsAdmin();
        $this->createPatient(['name' => 'Export Patient']);

        $result = $this->get('/patients/export');

        $result->assertStatus(200);
        $result->assertHeader('Content-Type', 'text/csv');
    }
}
