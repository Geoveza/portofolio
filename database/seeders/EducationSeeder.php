<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * FIX: Now updates existing data or creates new if not exists
     */
    public function run(): void
    {
        $educations = [
            [
                'institution' => 'Universitas Pembangunan Nasional Veteran Jawa Timur',
                'degree' => 'Bachelor of Public Administration',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/id/1/12/Logo_UPN_Veteran_Jawa_Timur.png',
                'description' => 'Studied public administration with focus on governance, policy analysis, and public service management.',
                'start_date' => '2021-08-01',
                'end_date' => '2025-11-28',
                'is_current' => false,
                'type' => 'formal',
                'location' => 'Surabaya, Indonesia',
                'order' => 1,
            ],
            [
                'institution' => 'Google Developer Student Clubs',
                'degree' => 'Developer Bootcamp',
                'logo_url' => 'https://gdsc-upnvjt.github.io/assets/images/logo2.png',
                'description' => 'Participating in a GDSC Developer Bootcamp provides skills in programming fundamentals, web or mobile application development, API integration, and version control using Git and GitHub. It also enhances problem-solving, debugging, and understanding of the software development lifecycle, along with basic UI/UX and cloud concepts. In addition, participants gain teamwork, communication, time management, and critical thinking skills through hands-on projects and real-world application development.',
                'start_date' => '2022-03-01',
                'end_date' => '2025-11-28',
                'is_current' => false,
                'type' => 'bootcamp',
                'location' => 'Remote',
                'order' => 2,
            ],
            [
                'institution' => 'JuaraGCP by Google Cloud',
                'degree' => 'Cloud Bootcamp',
                'logo_url' => 'https://i.postimg.cc/HWVzrqST/png-clipart-google-cloud-platform-cloud-computing-microsoft-azure-business-cloud-computing-text-logo.png',
                'description' => 'Participating in the JuaraGCP Cloud Bootcamp by Google Cloud equips participants with fundamental to intermediate skills in cloud computing, including deploying and managing applications on Google Cloud Platform, working with services such as Compute Engine, Cloud Storage, and Kubernetes, and understanding cloud architecture and security basics. The program also strengthens hands-on experience through labs and real-world scenarios, while developing problem-solving, technical adaptability, and practical knowledge of cloud-based infrastructure.',
                'start_date' => '2019-09-01',
                'end_date' => '2026-3-15',
                'is_current' => false,
                'type' => 'bootcamp',
                'location' => 'Remote',
                'order' => 3,
            ],
            [
                'institution' => 'Alchemy',
                'degree' => 'Blockchain Bootcamp',
                'logo_url' => 'https://scontent.fsub32-2.fna.fbcdn.net/v/t39.30808-6/273304918_387063223224203_5539428030146943942_n.png?_nc_cat=105&ccb=1-7&_nc_sid=1d70fc&_nc_eui2=AeFKs7IhDX63UedB6V_Zl0FYYIIQdXpUQIBgghB1elRAgFJXAxHYSrZpzm21Hot9K2G7ozJM5n5EayQqhY4i83Ex&_nc_ohc=-9-nbehI--4Q7kNvwGsVsuY&_nc_oc=AdpZHNt3UUC1Q0EWGZ4lqI_8DR6FWSIBgZ280xWkuYOQS7SNORZ4L1RBbbhI6OviR_8RPxtEduTLAOfiW72kg7KM&_nc_zt=23&_nc_ht=scontent.fsub32-2.fna&_nc_gid=kBLwRZSFlAaA4ngErdjJhw&_nc_ss=7a32e&oh=00_AfzpM_4WoFRTpJL5uY5aJHaByB94r6vMyiDezQgC7SiYhw&oe=69C53676',
                'description' => 'Participating in the Alchemy University Blockchain Bootcamp provides foundational to intermediate skills in blockchain development, including understanding blockchain architecture, smart contracts, and decentralized applications (dApps). Participants gain hands-on experience using tools such as Solidity, Ethereum, and Web3 libraries, while also learning how to deploy and interact with smart contracts. The program strengthens problem-solving skills and offers practical exposure to building real-world Web3 applications.',
                'start_date' => '2020-06-01',
                'end_date' => '2021-12-31',
                'is_current' => false,
                'type' => 'certification',
                'location' => 'Online',
                'order' => 4,
            ]
        ];

        $updatedCount = 0;
        $createdCount = 0;

        foreach ($educations as $education) {
            // FIX: Use updateOrCreate to update existing or create new
            $record = Education::updateOrCreate(
                ['institution' => $education['institution']], // Search by institution name
                $education // Update all fields
            );

            if ($record->wasRecentlyCreated) {
                $createdCount++;
            } else {
                $updatedCount++;
            }
        }

        $this->command->info("Education data updated: {$updatedCount} updated, {$createdCount} created.");
    }
}
