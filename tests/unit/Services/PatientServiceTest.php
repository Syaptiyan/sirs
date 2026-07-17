<?php

namespace Tests\Unit\Services;

use App\Models\PatientModel;
use App\Models\PatientAllergyModel;
use App\Models\PatientChronicDiseaseModel;
use App\Models\PatientDocumentModel;
use App\Models\VisitModel;
use App\Services\Patient\PatientService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class PatientServiceTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;

    protected $DBGroup = 'tests';
    protected $refresh = false;
    private PatientService $patientService;
    private PatientModel $patientModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->patientService = new PatientService();
        $this->patientModel = new PatientModel();
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

    public function testGetAllReturnsPatients(): void
    {
        $this->createPatient(['name' => 'Patient A']);
        $this->createPatient(['name' => 'Patient B']);

        $result = $this->patientService->getAll();

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('meta', $result);
        $this->assertGreaterThanOrEqual(2, $result['meta']['total']);
    }

    public function testGetAllWithSearchFilter(): void
    {
        $this->createPatient(['name' => 'Ahmad Fauzi']);
        $this->createPatient(['name' => 'Budi Santoso']);

        $result = $this->patientService->getAll('Ahmad');

        $this->assertGreaterThanOrEqual(1, $result['meta']['total']);
        $found = false;
        foreach ($result['data'] as $patient) {
            if (str_contains($patient->name, 'Ahmad')) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);
    }

    public function testGetAllWithGenderFilter(): void
    {
        $this->createPatient(['name' => 'Male Patient', 'gender' => 'M']);
        $this->createPatient(['name' => 'Female Patient', 'gender' => 'F']);

        $result = $this->patientService->getAll(null, 'F');

        foreach ($result['data'] as $patient) {
            $this->assertEquals('F', $patient->gender);
        }
    }

    public function testGetAllWithPagination(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->createPatient(['name' => "Patient {$i}"]);
        }

        $result = $this->patientService->getAll(null, null, null, 1, 2);

        $this->assertCount(2, $result['data']);
        $this->assertEquals(1, $result['meta']['page']);
        $this->assertEquals(2, $result['meta']['per_page']);
    }

    public function testGetByUuidReturnsPatient(): void
    {
        $patient = $this->createPatient();

        $result = $this->patientService->getByUuid($patient->uuid);

        $this->assertIsObject($result);
        $this->assertEquals($patient->id, $result->id);
    }

    public function testGetByUuidReturnsNullForNonExistent(): void
    {
        $result = $this->patientService->getByUuid('non-existent-uuid');

        $this->assertNull($result);
    }

    public function testCreatePatientSuccess(): void
    {
        $data = [
            'name' => 'New Patient',
            'gender' => 'F',
            'birth_date' => '1995-05-20',
            'phone' => '08123456789',
        ];

        $patient = $this->patientService->create($data);

        $this->assertIsObject($patient);
        $this->assertEquals('New Patient', $patient->name);
        $this->assertNotNull($patient->uuid);
        $this->assertNotNull($patient->mr_number);
        $this->assertStringStartsWith('RM-', $patient->mr_number);
    }

    public function testCreatePatientGeneratesUniqueMRN(): void
    {
        $patient1 = $this->patientService->create([
            'name' => 'Patient 1',
            'gender' => 'M',
            'birth_date' => '1990-01-01',
        ]);

        $patient2 = $this->patientService->create([
            'name' => 'Patient 2',
            'gender' => 'F',
            'birth_date' => '1992-02-02',
        ]);

        $this->assertNotEquals($patient1->mr_number, $patient2->mr_number);
    }

    public function testUpdatePatientSuccess(): void
    {
        $patient = $this->createPatient();

        $result = $this->patientService->update($patient->uuid, [
            'name' => 'Updated Name',
            'phone' => '08987654321',
        ]);

        $this->assertTrue($result);

        $updated = $this->patientModel->find($patient->id);
        $this->assertEquals('Updated Name', $updated->name);
        $this->assertEquals('08987654321', $updated->phone);
    }

    public function testUpdateNonExistentPatientReturnsFalse(): void
    {
        $result = $this->patientService->update('non-existent-uuid', [
            'name' => 'Updated Name',
        ]);

        $this->assertFalse($result);
    }

    public function testDeletePatientSuccess(): void
    {
        $patient = $this->createPatient();

        $result = $this->patientService->delete($patient->uuid);

        $this->assertTrue($result);
        $this->assertNull($this->patientModel->find($patient->id));
    }

    public function testDeleteNonExistentPatientReturnsFalse(): void
    {
        $result = $this->patientService->delete('non-existent-uuid');

        $this->assertFalse($result);
    }

    public function testGetAllergiesReturnsEmptyForNonExistentPatient(): void
    {
        $result = $this->patientService->getAllergies('non-existent-uuid');

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testAddAllergySuccess(): void
    {
        $patient = $this->createPatient();

        $allergy = $this->patientService->addAllergy($patient->uuid, [
            'allergy_name' => 'Penisilin',
            'severity' => 'severe',
            'reaction' => 'Anafilaksis',
        ]);

        $this->assertIsObject($allergy);
        $this->assertEquals('Penisilin', $allergy->allergy_name);
        $this->assertEquals($patient->id, $allergy->patient_id);
    }

    public function testAddAllergyToNonExistentPatientReturnsNull(): void
    {
        $result = $this->patientService->addAllergy('non-existent-uuid', [
            'allergy_name' => 'Penisilin',
            'severity' => 'severe',
        ]);

        $this->assertNull($result);
    }

    public function testRemoveAllergySuccess(): void
    {
        $patient = $this->createPatient();
        $allergy = $this->patientService->addAllergy($patient->uuid, [
            'allergy_name' => 'Penisilin',
            'severity' => 'severe',
        ]);

        $result = $this->patientService->removeAllergy($allergy->uuid);

        $this->assertTrue($result);
    }

    public function testRemoveNonExistentAllergyReturnsFalse(): void
    {
        $result = $this->patientService->removeAllergy('non-existent-uuid');

        $this->assertFalse($result);
    }

    public function testGetChronicDiseasesReturnsEmptyForNonExistentPatient(): void
    {
        $result = $this->patientService->getChronicDiseases('non-existent-uuid');

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testAddChronicDiseaseSuccess(): void
    {
        $patient = $this->createPatient();

        $disease = $this->patientService->addChronicDisease($patient->uuid, [
            'disease_name' => 'Diabetes Melitus Tipe 2',
            'diagnosed_date' => '2020-06-15',
            'treatment' => 'Metformin 500mg',
        ]);

        $this->assertIsObject($disease);
        $this->assertEquals('Diabetes Melitus Tipe 2', $disease->disease_name);
        $this->assertEquals($patient->id, $disease->patient_id);
    }

    public function testGetDocumentsReturnsEmptyForNonExistentPatient(): void
    {
        $result = $this->patientService->getDocuments('non-existent-uuid');

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testAddDocumentSuccess(): void
    {
        $patient = $this->createPatient();

        $document = $this->patientService->addDocument($patient->uuid, [
            'document_name' => 'Hasil Lab',
            'document_type' => 'lab_result',
            'file_path' => 'uploads/patients/lab_result.pdf',
            'file_name' => 'lab_result.pdf',
            'file_size' => 1024,
            'mime_type' => 'application/pdf',
        ]);

        $this->assertIsObject($document);
        $this->assertEquals('Hasil Lab', $document->document_name);
        $this->assertEquals($patient->id, $document->patient_id);
    }

    public function testGetVisitsReturnsEmptyForNonExistentPatient(): void
    {
        $result = $this->patientService->getVisits('non-existent-uuid');

        $this->assertIsArray($result);
        $this->assertEmpty($result['data']);
        $this->assertEquals(0, $result['meta']['total']);
    }

    public function testGenerateMRNFormat(): void
    {
        $mrn = $this->patientService->generateMRN();

        $this->assertMatchesRegularExpression('/^RM-\d{8}-\d{4}$/', $mrn);
    }

    public function testExportReturnsCsvString(): void
    {
        $this->createPatient(['name' => 'Export Patient']);

        $csv = $this->patientService->export('csv');

        $this->assertIsString($csv);
        $this->assertStringContainsString('Nama', $csv);
        $this->assertStringContainsString('Export Patient', $csv);
    }
}
