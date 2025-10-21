<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Standard Operating Procedure (SOP)') }}
            </h2>
            <a href="#" onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full shadow hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 17H7v4h10v-4zM7 7V3h10v4M7 13h10m-12 4H5a2 2 0 01-2-2v-4a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2h-2"/></svg>
                <span class="text-sm font-medium">Print</span>
            </a>
        </div>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Version 1.0 • Effective: October 2025 • Prepared by: CEO – Kazi Abu Sifat</p>
    </x-slot>

    <div x-data="{progress:0}" x-init="window.addEventListener('scroll',()=>{progress=Math.min(100,(window.scrollY/(document.documentElement.scrollHeight-window.innerHeight))*100)})">
        <div class="fixed top-0 left-0 right-0 z-50 h-1 bg-gray-200/60 dark:bg-gray-700/60 pointer-events-none">
            <div class="h-1 bg-indigo-600 transition-all" :style="`width:${progress}%;`"></div>
        </div>
    </div>

        <main class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
            <section class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-10">
                    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">1. Purpose</h2>
                        <p class="mt-3 text-gray-700 dark:text-gray-300">To define clear responsibilities, communication flow, and standard processes for client acquisition, project execution, and post-delivery support at Ryven Global LLC.</p>
                    </div>

                    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">2. Team Structure</h2>
                        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            @php
                                $roles = [
                                    ['role' => 'CEO', 'desc' => 'Leadership, approvals, client relations, strategic direction'],
                                    ['role' => 'Manager', 'desc' => 'Project coordination, team communication, reporting'],
                                    ['role' => 'Business Development Manager', 'desc' => 'Client acquisition, outreach, proposals'],
                                    ['role' => 'UI/UX Designer', 'desc' => 'User experience, interface design, prototyping'],
                                    ['role' => 'Full Stack Developers', 'desc' => 'Full-cycle development and integration'],
                                    ['role' => 'Frontend Developer', 'desc' => 'UI implementation and responsive layouts'],
                                    ['role' => 'Backend Developer', 'desc' => 'API development, database and logic'],
                                    ['role' => 'QA Engineer', 'desc' => 'Testing, bug tracking, release verification'],
                                ];
                            @endphp
                            @foreach ($roles as $r)
                                <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-1 h-2.5 w-2.5 rounded-full bg-indigo-500"></div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $r['role'] }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $r['desc'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                         <h2 class="text-xl font-semibold text-gray-900 dark:text-white">3. Workflow Overview</h2>
                         <ol class="mt-6 relative border-s-2 border-dashed border-indigo-200 dark:border-indigo-300/40 ps-6 space-y-4">
                            @php
                                $stages = [
                                    [
                                        'title' => 'Stage 1 – Lead Generation & Client Acquisition',
                                        'resp' => 'Business Development Manager, Manager, CEO',
                                        'items' => [
                                            'Identify leads on LinkedIn, Upwork, Fiverr, and via email campaigns.',
                                            'Research needs and prepare tailored pitch/proposal.',
                                            'Send outreach and schedule discovery calls.',
                                            'Hold discovery to understand scope and budget.',
                                            'CEO approves final quotation and timeline.',
                                            'Manager ensures proposal delivery and follow-up.',
                                        ],
                                        'deliverables' => ['Client brief','Proposal/quotation','Follow-up tracker'],
                                    ],
                                    [
                                        'title' => 'Stage 2 – Client Onboarding',
                                        'resp' => 'Business Development Manager, Manager, CEO',
                                        'items' => [
                                            'Send onboarding email with welcome kit.',
                                            'Sign NDA, Contract, and Payment Agreement.',
                                            'Create project workspace (Drive/Notion).',
                                            'Setup CRM project, Discord channels, WhatsApp group, email thread.',
                                            'Assign developers, designer, and QA.',
                                        ],
                                        'deliverables' => ['Signed documents','Workspace setup','Team allocation'],
                                    ],
                                    [
                                        'title' => 'Stage 3 – Project Kickoff & Planning',
                                        'resp' => 'Manager, UI/UX Designer, Developers',
                                        'items' => [
                                            'Kickoff meeting with team and client.',
                                            'Create roadmap and sprint plan.',
                                            'UI/UX prepares wireframes and gets approval.',
                                            'Developers set up repo and environments.',
                                        ],
                                        'deliverables' => ['Kickoff notes','Roadmap/Sprint plan','Approved designs'],
                                    ],
                                    [
                                        'title' => 'Stage 4 – Development & Communication',
                                        'resp' => 'Full Stack Developers, Interns, Manager',
                                        'items' => [
                                            'Follow sprint tasks as per roadmap.',
                                            'Daily or bi-weekly updates in tracker.',
                                            'Weekly client sync by Manager.',
                                            'Meaningful Git commit messages.',
                                            'API docs coordinate Backend & Frontend.',
                                            'QA starts testing each sprint.',
                                        ],
                                        'deliverables' => ['Code commits','Sprint progress','QA feedback'],
                                    ],
                                    [
                                        'title' => 'Stage 5 – Testing & QA',
                                        'resp' => 'QA Engineer, Manager, Developers',
                                        'items' => [
                                            'Manual and automated tests.',
                                            'Log bugs in CRM.',
                                            'Developers fix and mark resolutions.',
                                            'Pre-deploy review by Manager & CEO.',
                                        ],
                                        'deliverables' => ['QA report','Bug tracker','Final test approval'],
                                    ],
                                    [
                                        'title' => 'Stage 6 – Deployment & Delivery',
                                        'resp' => 'Full Stack Developers, Manager, CEO',
                                        'items' => [
                                            'Deploy to staging → review → live.',
                                            'Manager conducts live demo.',
                                            'CEO final delivery approval.',
                                            'Provide training/docs.',
                                        ],
                                        'deliverables' => ['Live URL','User manual/video','Handover checklist'],
                                    ],
                                    [
                                        'title' => 'Stage 7 – 30-Day Support & Maintenance',
                                        'resp' => 'Manager, Developers, QA',
                                        'items' => [
                                            'Bug fixes and minor updates.',
                                            'Maintain client communication log.',
                                            'Record version changes.',
                                        ],
                                        'deliverables' => ['Support log','Version updates'],
                                    ],
                                    [
                                        'title' => 'Stage 8 – Client Feedback & Review',
                                        'resp' => 'Manager, Business Development Manager',
                                        'items' => [
                                            'Request reviews on Clutch/Google/Facebook.',
                                            'Send personal thanks via email or WhatsApp.',
                                            'Collect testimonials for portfolio.',
                                        ],
                                        'deliverables' => ['Review proofs','Case study draft'],
                                    ],
                                    [
                                        'title' => 'Stage 9 – Retention & Upsell',
                                        'resp' => 'CEO, Business Development Manager',
                                        'items' => [
                                            'Offer 20–30% discount for next project/referral.',
                                            'Propose monthly maintenance.',
                                            'Add to Ryven newsletter.',
                                        ],
                                        'deliverables' => ['Referral offer','Subscription proposal'],
                                    ],
                                ];
                            @endphp

                             @php
                                 $stageAnchors = [
                                     'Stage 3 – Project Kickoff & Planning' => 'kickoff-template',
                                     'Stage 5 – Testing & QA' => 'qa-checklist',
                                     'Stage 6 – Deployment & Delivery' => 'deployment-guide',
                                 ];
                             @endphp

                             @foreach ($stages as $index => $stage)
                                 <li class="relative stage-item" @if(isset($stageAnchors[$stage['title']])) id="{{ $stageAnchors[$stage['title']] }}" @endif>
                                    <div class="absolute -start-1 top-2 h-3 w-3 rounded-full bg-indigo-500"></div>
                                    <details open class="ms-6 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-0 shadow-sm overflow-hidden stage-card">
                                        <summary class="flex cursor-pointer items-center justify-between gap-4 px-5 py-4 select-none">
                                            <div>
                                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $stage['title'] }}</h3>
                                                <span class="mt-1 inline-flex items-center rounded-full bg-indigo-50 dark:bg-indigo-400/20 px-2 py-0.5 text-xs font-medium text-indigo-700 dark:text-indigo-200">Responsible: {{ $stage['resp'] }}</span>
                                            </div>
                                            <svg class="chevron h-4 w-4 text-gray-500 dark:text-gray-300 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                                        </summary>
                                        <div class="px-5 pb-5">
                                            <ul class="mt-1 list-disc ps-5 text-sm text-gray-700 dark:text-gray-300 space-y-1">
                                                @foreach ($stage['items'] as $it)
                                                    <li>{{ $it }}</li>
                                                @endforeach
                                            </ul>
                                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                                @foreach ($stage['deliverables'] as $d)
                                                    <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-700/50 px-2.5 py-1 text-xs font-medium text-gray-700 dark:text-gray-200 ring-1 ring-inset ring-gray-200 dark:ring-gray-600">{{ $d }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </details>
                                </li>
                            @endforeach
                        </ol>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">4. Communication Protocol</h2>
                            <div class="mt-4 overflow-x-auto">
                                <table class="min-w-full text-left text-sm">
                                    <thead class="text-gray-600 dark:text-gray-300">
                                        <tr>
                                            <th class="py-2">Type</th>
                                            <th class="py-2">Tool</th>
                                            <th class="py-2">Frequency</th>
                                            <th class="py-2">Responsible</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-700 dark:text-gray-300">
                                        @php
                                            $comm = [
                                                ['Daily Task Updates','CRM','Daily','All Developers'],
                                                ['Weekly Report','CRM / Google Docs','Weekly','Manager'],
                                                ['Client Sync Call','Zoom / Google Meet','Weekly','Manager + CEO'],
                                                ['Internal Review','Discord / Meet','Bi-weekly','CEO + Team'],
                                                ['QA Report','CRM','Per sprint','QA Engineer'],
                                                ['Final Delivery','Email + Call','Once','CEO + Manager'],
                                            ];
                                        @endphp
                                        @foreach ($comm as $c)
                                            <tr class="border-t border-gray-100">
                                                <td class="py-2">{{ $c[0] }}</td>
                                                <td class="py-2">{{ $c[1] }}</td>
                                                <td class="py-2">{{ $c[2] }}</td>
                                                <td class="py-2">{{ $c[3] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">5. Documentation Standards</h2>
                            <ul class="mt-3 list-disc ps-5 text-gray-700 dark:text-gray-300 space-y-1">
                                <li>Every project contains a <code>/docs</code> folder with SRS, designs, deployment, API, QA.</li>
                                <li>Naming: <code>clientname_projectname_phase_date.docx</code></li>
                            </ul>
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">6. Code & Repository Policy</h2>
                            <ul class="mt-3 list-disc ps-5 text-gray-700 dark:text-gray-300 space-y-1">
                                <li>Private repositories on GitHub/GitLab.</li>
                                <li>Branches: <code>main</code> → production, <code>dev</code> → sprint, <code>feature/*</code> per feature.</li>
                                <li>Commits include task ID/purpose, e.g., <code>feat(auth): add JWT login route</code>.</li>
                            </ul>
                        </div>
                        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">7. Meetings & Reporting</h2>
                            <ul class="mt-3 list-disc ps-5 text-gray-700 dark:text-gray-300 space-y-1">
                                <li>Daily Standup (optional) — Devs & QA</li>
                                <li>Weekly Review (mandatory) — Manager & CEO</li>
                                <li>Monthly Business Review — BDM & CEO</li>
                            </ul>
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">8. Performance Evaluation</h2>
                            @php
                                $perf = [
                                    ['Developers','Code quality, deadlines, communication'],
                                    ['Interns','Learning progress, discipline, responsiveness'],
                                    ['UI/UX','Creativity, turnaround time, client feedback'],
                                    ['QA','Bug coverage, testing accuracy'],
                                    ['Manager','Coordination, communication, reporting'],
                                    ['BDM','Leads, deals closed, satisfaction'],
                                    ['CEO','Strategic growth, morale, profitability'],
                                ];
                            @endphp
                            <ul class="mt-3 space-y-2">
                                @foreach ($perf as $p)
                                    <li class="flex items-start gap-3">
                                        <span class="mt-1 inline-block h-2.5 w-2.5 rounded-full bg-purple-500"></span>
                                        <p class="text-gray-700 dark:text-gray-300"><span class="font-medium text-gray-900 dark:text-white">{{ $p[0] }}:</span> {{ $p[1] }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">9. Tools & Platforms</h2>
                            <ul class="mt-3 grid grid-cols-2 gap-2 text-gray-700 dark:text-gray-300 text-sm">
                                <li><span class="font-medium">Communication</span>: Slack, Gmail, WhatsApp</li>
                                <li><span class="font-medium">Project Mgmt</span>: CRM, WhatsApp, Email, Google Meet, Zoom</li>
                                <li><span class="font-medium">Design</span>: Figma, Adobe XD</li>
                                <li><span class="font-medium">Development</span>: VS Code, GitHub, Postman, Vercel, Railway, AWS</li>
                                <li><span class="font-medium">QA</span>: Notion, BrowserStack, Testrail</li>
                                <li><span class="font-medium">Docs</span>: Google Docs, Notion, Google Drive</li>
                                <li><span class="font-medium">Deployment</span>: Vercel, Railway, AWS, Render, Cloudflare, Netlify</li>
                                <li><span class="font-medium">Payment</span>: Stripe, PayPal, Bank Transfer</li>
                                <li><span class="font-medium">Hosting</span>: Vercel, Railway, AWS, Render, Cloudflare, Netlify</li>
                                <li><span class="font-medium">CI/CD</span>: GitHub Actions, GitLab CI, CircleCI, Travis CI, Jenkins</li>
                                <li><span class="font-medium">Monitoring</span>: New Relic, Datadog, Sentry, Logz.io, Loggly</li>
                                <li><span class="font-medium">Logging</span>: Logz.io, Loggly, Papertrail, Graylog</li>
                                <li><span class="font-medium">Analytics</span>: Google Analytics, Mixpanel, Amplitude, Heap</li>
                                <li><span class="font-medium">Testing</span>: Jest, Mocha, Chai, Sinon, Enzyme</li>
                                <li><span class="font-medium">Performance</span>: Lighthouse, PageSpeed Insights, Web Vitals</li>
                                <li><span class="font-medium">Accessibility</span>: Axe, WAVE, Tenon, Aria-Labs</li>
                                <li><span class="font-medium">Security</span>: OWASP, ZAP, Burp Suite, Metasploit</li>
                            </ul>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">10. Security & Compliance</h2>
                        <ul class="mt-3 list-disc ps-5 text-gray-700 dark:text-gray-300 space-y-1">
                            <li>All client data must remain confidential.</li>
                            <li>NDA and IP transfer agreements before project start.</li>
                            <li>No third-party sharing without approval.</li>
                            <li>Follow GDPR and local privacy standards.</li>
                            <li>Follow OWASP and local security standards.</li>
                            <li>Follow PCI DSS and local security standards.</li>
                            <li>Follow HIPAA and local security standards.</li>
                            <li>Follow CCPA and local security standards.</li>
                        </ul>
                    </div>
                </div>

                <aside class="space-y-6 lg:sticky lg:top-8 self-start">
                    <div class="rounded-2xl border border-indigo-200 dark:border-indigo-400/30 bg-gradient-to-br from-indigo-50 to-white dark:from-indigo-950/30 dark:to-gray-800 p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-indigo-900 dark:text-indigo-200">At-a-glance</h3>
                        <ul class="mt-3 space-y-2 text-sm text-indigo-900/80 dark:text-indigo-200/80">
                            <li>9 workflow stages from Lead Gen to Upsell</li>
                            <li>Clear role responsibilities</li>
                            <li>Defined communication cadence</li>
                            <li>Security and compliance ready</li>
                        </ul>
                        <a href="#" onclick="window.print()" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full shadow hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 17H7v4h10v-4zM7 7V3h10v4M7 13h10m-12 4H5a2 2 0 01-2-2v-4a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2h-2"/></svg>
                            <span class="text-sm font-medium">Print</span>
                        </a>
                    </div>
                     <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                         <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Links</h3>
                         <ul class="mt-3 space-y-2 text-sm text-gray-700 dark:text-gray-300">
                             <li><a class="hover:text-indigo-600" href="#kickoff-template">Kickoff Template</a></li>
                             <li><a class="hover:text-indigo-600" href="#qa-checklist">QA Checklist</a></li>
                             <li><a class="hover:text-indigo-600" href="#deployment-guide">Deployment Guide</a></li>
                         </ul>
                     </div>
                </aside>
            </section>
        </main>
    </x-app-layout>


