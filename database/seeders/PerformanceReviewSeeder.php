<?php

namespace Database\Seeders;

use App\Models\ReviewTemplate;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class PerformanceReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedReviewTemplates();
        $this->seedSkills();
    }

    private function seedReviewTemplates(): void
    {
        // Annual Review Template
        ReviewTemplate::create([
            'name' => 'Annual Performance Review',
            'description' => 'Comprehensive annual review covering all aspects of performance',
            'is_default' => true,
            'is_active' => true,
            'rating_scale' => [
                ['value' => 1, 'label' => 'Needs Improvement', 'description' => 'Performance is below expectations'],
                ['value' => 2, 'label' => 'Meets Expectations', 'description' => 'Performance meets job requirements'],
                ['value' => 3, 'label' => 'Exceeds Expectations', 'description' => 'Performance exceeds job requirements'],
                ['value' => 4, 'label' => 'Outstanding', 'description' => 'Exceptional performance, role model'],
            ],
            'sections' => [
                [
                    'name' => 'Job Knowledge',
                    'description' => 'Understanding of job responsibilities and technical requirements',
                    'weight' => 20,
                ],
                [
                    'name' => 'Quality of Work',
                    'description' => 'Accuracy, thoroughness, and reliability of work output',
                    'weight' => 20,
                ],
                [
                    'name' => 'Productivity',
                    'description' => 'Efficiency and quantity of work completed',
                    'weight' => 15,
                ],
                [
                    'name' => 'Communication',
                    'description' => 'Effectiveness in written and verbal communication',
                    'weight' => 15,
                ],
                [
                    'name' => 'Teamwork',
                    'description' => 'Collaboration and support provided to team members',
                    'weight' => 15,
                ],
                [
                    'name' => 'Initiative',
                    'description' => 'Proactiveness and self-motivation',
                    'weight' => 15,
                ],
            ],
        ]);

        // Quarterly Review Template
        ReviewTemplate::create([
            'name' => 'Quarterly Performance Review',
            'description' => 'Focused quarterly check-in on key performance areas',
            'is_default' => false,
            'is_active' => true,
            'rating_scale' => [
                ['value' => 1, 'label' => 'Below Target', 'description' => 'Not meeting quarterly goals'],
                ['value' => 2, 'label' => 'On Track', 'description' => 'Meeting quarterly expectations'],
                ['value' => 3, 'label' => 'Exceeding', 'description' => 'Exceeding quarterly targets'],
            ],
            'sections' => [
                [
                    'name' => 'Goal Achievement',
                    'description' => 'Progress on quarterly goals and OKRs',
                    'weight' => 40,
                ],
                [
                    'name' => 'Key Deliverables',
                    'description' => 'Completion of major projects and tasks',
                    'weight' => 30,
                ],
                [
                    'name' => 'Collaboration',
                    'description' => 'Cross-functional work and team support',
                    'weight' => 30,
                ],
            ],
        ]);

        // Probation Review Template
        ReviewTemplate::create([
            'name' => 'Probation Period Review',
            'description' => 'Evaluation for employees completing their probation period',
            'is_default' => false,
            'is_active' => true,
            'rating_scale' => [
                ['value' => 1, 'label' => 'Not Recommended', 'description' => 'Does not meet requirements for continuation'],
                ['value' => 2, 'label' => 'Extended Probation', 'description' => 'Requires additional evaluation period'],
                ['value' => 3, 'label' => 'Recommended', 'description' => 'Ready for permanent employment'],
            ],
            'sections' => [
                [
                    'name' => 'Learning & Adaptation',
                    'description' => 'Ability to learn and adapt to company culture',
                    'weight' => 25,
                ],
                [
                    'name' => 'Technical Competence',
                    'description' => 'Demonstration of required technical skills',
                    'weight' => 30,
                ],
                [
                    'name' => 'Cultural Fit',
                    'description' => 'Alignment with company values and team dynamics',
                    'weight' => 25,
                ],
                [
                    'name' => 'Attendance & Punctuality',
                    'description' => 'Reliability and time management',
                    'weight' => 20,
                ],
            ],
        ]);
    }

    private function seedSkills(): void
    {
        // Technical Skills
        $technicalSkills = [
            ['name' => 'PHP', 'description' => 'PHP programming language'],
            ['name' => 'Laravel', 'description' => 'Laravel framework'],
            ['name' => 'JavaScript', 'description' => 'JavaScript programming'],
            ['name' => 'Vue.js', 'description' => 'Vue.js framework'],
            ['name' => 'React', 'description' => 'React framework'],
            ['name' => 'MySQL', 'description' => 'MySQL database'],
            ['name' => 'PostgreSQL', 'description' => 'PostgreSQL database'],
            ['name' => 'Git', 'description' => 'Version control with Git'],
            ['name' => 'API Development', 'description' => 'RESTful API design and development'],
            ['name' => 'Docker', 'description' => 'Containerization with Docker'],
            ['name' => 'AWS', 'description' => 'Amazon Web Services'],
            ['name' => 'Testing', 'description' => 'Unit and integration testing'],
        ];

        foreach ($technicalSkills as $skill) {
            Skill::create(array_merge($skill, ['category' => 'technical', 'is_active' => true]));
        }

        // Soft Skills
        $softSkills = [
            ['name' => 'Communication', 'description' => 'Written and verbal communication'],
            ['name' => 'Problem Solving', 'description' => 'Analytical and creative problem solving'],
            ['name' => 'Time Management', 'description' => 'Prioritization and deadline management'],
            ['name' => 'Adaptability', 'description' => 'Flexibility and learning agility'],
            ['name' => 'Attention to Detail', 'description' => 'Thoroughness and accuracy'],
            ['name' => 'Critical Thinking', 'description' => 'Analysis and evaluation'],
            ['name' => 'Collaboration', 'description' => 'Teamwork and cooperation'],
            ['name' => 'Creativity', 'description' => 'Innovation and original thinking'],
        ];

        foreach ($softSkills as $skill) {
            Skill::create(array_merge($skill, ['category' => 'soft', 'is_active' => true]));
        }

        // Leadership Skills
        $leadershipSkills = [
            ['name' => 'Team Leadership', 'description' => 'Leading and motivating teams'],
            ['name' => 'Decision Making', 'description' => 'Strategic decision making'],
            ['name' => 'Delegation', 'description' => 'Effective task delegation'],
            ['name' => 'Mentoring', 'description' => 'Coaching and developing others'],
            ['name' => 'Conflict Resolution', 'description' => 'Managing and resolving conflicts'],
            ['name' => 'Strategic Thinking', 'description' => 'Long-term planning and vision'],
        ];

        foreach ($leadershipSkills as $skill) {
            Skill::create(array_merge($skill, ['category' => 'leadership', 'is_active' => true]));
        }

        // Domain Knowledge
        $domainSkills = [
            ['name' => 'E-commerce', 'description' => 'E-commerce platforms and processes'],
            ['name' => 'Fintech', 'description' => 'Financial technology domain'],
            ['name' => 'Healthcare', 'description' => 'Healthcare industry knowledge'],
            ['name' => 'SaaS', 'description' => 'Software as a Service business model'],
            ['name' => 'Agile/Scrum', 'description' => 'Agile methodologies'],
        ];

        foreach ($domainSkills as $skill) {
            Skill::create(array_merge($skill, ['category' => 'domain', 'is_active' => true]));
        }

        // Tools
        $toolSkills = [
            ['name' => 'Jira', 'description' => 'Project management with Jira'],
            ['name' => 'Figma', 'description' => 'UI/UX design with Figma'],
            ['name' => 'Postman', 'description' => 'API testing with Postman'],
            ['name' => 'VS Code', 'description' => 'Visual Studio Code IDE'],
        ];

        foreach ($toolSkills as $skill) {
            Skill::create(array_merge($skill, ['category' => 'tool', 'is_active' => true]));
        }
    }
}
