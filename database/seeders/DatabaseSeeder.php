<?php

namespace Database\Seeders;

use App\Models\Act;
use App\Models\Appointment;
use App\Models\Attendance;
use App\Models\BankAccount;
use App\Models\CaseCategory;
use App\Models\City;
use App\Models\Client;
use App\Models\ClientCategory;
use App\Models\ConflictCheck;
use App\Models\Contact;
use App\Models\ContactCategory;
use App\Models\Country;
use App\Models\Court;
use App\Models\CourtCategory;
use App\Models\FirmSetting;
use App\Models\HearingDate;
use App\Models\Invoice;
use App\Models\Judgment;
use App\Models\Lawyer;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\LegalCase;
use App\Models\Payroll;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Stage;
use App\Models\State;
use App\Models\Task;
use App\Models\Tax;
use App\Models\TimeEntry;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. FIRM SETTINGS (LexVanguard Global LLP)
        // ==========================================
        FirmSetting::firstOrCreate([], [
            'firm_name' => 'LexVanguard Global Legal Partners',
            'tagline' => 'Autonomous Multi-Jurisdictional Litigation & Corporate Counsel',
            'email' => 'contact@lexvanguard.law',
            'phone' => '+1 (800) 555-LEGAL',
            'address' => '100 Chancery Lane, Legal Precinct, London & New York',
            'registration_no' => 'LLP-GLOBAL-2027-8910',
            'tax_vat_number' => 'GLOBAL-VAT-9921048',
            'currency' => 'USD',
            'currency_symbol' => '$',
            'timezone' => 'UTC',
            'date_format' => 'Y-m-d',
            'ai_model' => 'gemini-1.5-pro',
            'invoice_footer' => 'LexVanguard Fiduciary Trust Accounting • Attorney-Client Privileged Counsel',
        ]);

        // ==========================================
        // 2. GLOBAL JURISDICTIONS (All Country Support)
        // ==========================================
        $countriesData = [
            ['name' => 'United States', 'code' => 'US', 'phonecode' => '+1', 'currency' => 'USD', 'currency_symbol' => '$', 'flag' => '🇺🇸', 'region' => 'North America'],
            ['name' => 'United Kingdom', 'code' => 'GB', 'phonecode' => '+44', 'currency' => 'GBP', 'currency_symbol' => '£', 'flag' => '🇬🇧', 'region' => 'Europe'],
            ['name' => 'United Arab Emirates', 'code' => 'AE', 'phonecode' => '+971', 'currency' => 'AED', 'currency_symbol' => 'د.إ', 'flag' => '🇦🇪', 'region' => 'Middle East'],
            ['name' => 'Singapore', 'code' => 'SG', 'phonecode' => '+65', 'currency' => 'SGD', 'currency_symbol' => 'S$', 'flag' => '🇸🇬', 'region' => 'Asia-Pacific'],
            ['name' => 'India', 'code' => 'IN', 'phonecode' => '+91', 'currency' => 'INR', 'currency_symbol' => '₹', 'flag' => '🇮🇳', 'region' => 'South Asia'],
            ['name' => 'Canada', 'code' => 'CA', 'phonecode' => '+1', 'currency' => 'CAD', 'currency_symbol' => 'CA$', 'flag' => '🇨🇦', 'region' => 'North America'],
            ['name' => 'Australia', 'code' => 'AU', 'phonecode' => '+61', 'currency' => 'AUD', 'currency_symbol' => 'A$', 'flag' => '🇦🇺', 'region' => 'Asia-Pacific'],
            ['name' => 'Germany', 'code' => 'DE', 'phonecode' => '+49', 'currency' => 'EUR', 'currency_symbol' => '€', 'flag' => '🇩🇪', 'region' => 'European Union'],
            ['name' => 'France', 'code' => 'FR', 'phonecode' => '+33', 'currency' => 'EUR', 'currency_symbol' => '€', 'flag' => '🇫🇷', 'region' => 'European Union'],
            ['name' => 'Switzerland', 'code' => 'CH', 'phonecode' => '+41', 'currency' => 'CHF', 'currency_symbol' => 'CHF', 'flag' => '🇨🇭', 'region' => 'Europe'],
            ['name' => 'Japan', 'code' => 'JP', 'phonecode' => '+81', 'currency' => 'JPY', 'currency_symbol' => '¥', 'flag' => '🇯🇵', 'region' => 'East Asia'],
            ['name' => 'Saudi Arabia', 'code' => 'SA', 'phonecode' => '+966', 'currency' => 'SAR', 'currency_symbol' => 'ر.س', 'flag' => '🇸🇦', 'region' => 'Middle East'],
            ['name' => 'Hong Kong SAR', 'code' => 'HK', 'phonecode' => '+852', 'currency' => 'HKD', 'currency_symbol' => 'HK$', 'flag' => '🇭🇰', 'region' => 'East Asia'],
            ['name' => 'Netherlands', 'code' => 'NL', 'phonecode' => '+31', 'currency' => 'EUR', 'currency_symbol' => '€', 'flag' => '🇳🇱', 'region' => 'European Union'],
            ['name' => 'Brazil', 'code' => 'BR', 'phonecode' => '+55', 'currency' => 'BRL', 'currency_symbol' => 'R$', 'flag' => '🇧🇷', 'region' => 'Latin America'],
            ['name' => 'South Africa', 'code' => 'ZA', 'phonecode' => '+27', 'currency' => 'ZAR', 'currency_symbol' => 'R', 'flag' => '🇿🇦', 'region' => 'Africa'],
        ];

        $countries = [];
        foreach ($countriesData as $c) {
            $countries[$c['code']] = Country::updateOrCreate(['code' => $c['code']], $c);
        }

        // States & Cities
        $usStateNy = State::firstOrCreate(['name' => 'New York', 'country_id' => $countries['US']->id]);
        $usCityNyc = City::firstOrCreate(['name' => 'New York City', 'state_id' => $usStateNy->id]);

        $usStateDe = State::firstOrCreate(['name' => 'Delaware', 'country_id' => $countries['US']->id]);
        $usCityWilmington = City::firstOrCreate(['name' => 'Wilmington', 'state_id' => $usStateDe->id]);

        $ukLondonState = State::firstOrCreate(['name' => 'Greater London', 'country_id' => $countries['GB']->id]);
        $ukCityLondon = City::firstOrCreate(['name' => 'City of London', 'state_id' => $ukLondonState->id]);

        $uaeDubaiState = State::firstOrCreate(['name' => 'Dubai Financial District', 'country_id' => $countries['AE']->id]);
        $uaeCityDubai = City::firstOrCreate(['name' => 'DIFC Downtown', 'state_id' => $uaeDubaiState->id]);

        $sgState = State::firstOrCreate(['name' => 'Central Business District', 'country_id' => $countries['SG']->id]);
        $sgCity = City::firstOrCreate(['name' => 'Singapore Downtown', 'state_id' => $sgState->id]);

        $inStateDelhi = State::firstOrCreate(['name' => 'National Capital Territory', 'country_id' => $countries['IN']->id]);
        $inCityDelhi = City::firstOrCreate(['name' => 'New Delhi', 'state_id' => $inStateDelhi->id]);

        // ==========================================
        // 3. COURT CATEGORIES & COURTS
        // ==========================================
        $supremeCat = CourtCategory::firstOrCreate(['name' => 'Apex & Supreme Courts', 'code' => 'APEX']);
        $commCat = CourtCategory::firstOrCreate(['name' => 'Commercial Division & Chancery Courts', 'code' => 'COMM']);
        $intlArbCat = CourtCategory::firstOrCreate(['name' => 'International Commercial Arbitration Forums', 'code' => 'ARB']);
        $appellateCat = CourtCategory::firstOrCreate(['name' => 'Appellate & High Courts', 'code' => 'APP']);

        $sdnyCourt = Court::firstOrCreate(['name' => 'U.S. District Court (SDNY)'], [
            'court_category_id' => $commCat->id,
            'country_id' => $countries['US']->id,
            'state_id' => $usStateNy->id,
            'city_id' => $usCityNyc->id,
            'bench' => 'Commercial Litigation Division',
            'room_number' => 'Courtroom 12B',
            'location' => '500 Pearl St, Manhattan, New York',
            'phone' => '+1 (212) 805-0136',
        ]);

        $chanceryCourt = Court::firstOrCreate(['name' => 'Delaware Court of Chancery'], [
            'court_category_id' => $commCat->id,
            'country_id' => $countries['US']->id,
            'state_id' => $usStateDe->id,
            'city_id' => $usCityWilmington->id,
            'bench' => 'Chancellor & Vice-Chancellor Bench',
            'room_number' => 'Courtroom 3A',
            'location' => 'Leonard L. Williams Justice Center, Wilmington, DE',
            'phone' => '+1 (302) 255-0544',
        ]);

        $londonCommCourt = Court::firstOrCreate(['name' => 'High Court of Justice - Commercial Court'], [
            'court_category_id' => $commCat->id,
            'country_id' => $countries['GB']->id,
            'state_id' => $ukLondonState->id,
            'city_id' => $ukCityLondon->id,
            'bench' => 'Business and Property Courts of England and Wales',
            'room_number' => 'Court 26, Rolls Building',
            'location' => '7 Rolls Buildings, Fetter Lane, London EC4A 1NL',
            'phone' => '+44 (0)20 7947 6000',
        ]);

        $difcCourt = Court::firstOrCreate(['name' => 'DIFC Courts (Dubai International Financial Centre)'], [
            'court_category_id' => $commCat->id,
            'country_id' => $countries['AE']->id,
            'state_id' => $uaeDubaiState->id,
            'city_id' => $uaeCityDubai->id,
            'bench' => 'Court of First Instance - Technology and Construction Division (TCD)',
            'room_number' => 'Digital Courtroom 1',
            'location' => 'Building 5, DIFC Gate Precinct, Dubai',
            'phone' => '+971 4 427 3333',
        ]);

        $siccCourt = Court::firstOrCreate(['name' => 'Singapore International Commercial Court (SICC)'], [
            'court_category_id' => $commCat->id,
            'country_id' => $countries['SG']->id,
            'state_id' => $sgState->id,
            'city_id' => $sgCity->id,
            'bench' => 'International Judicial Bench (Civil & Commercial)',
            'room_number' => 'Courtroom 5C',
            'location' => '1 Supreme Court Lane, Singapore 178879',
            'phone' => '+65 6336 0644',
        ]);

        $delhiHighCourt = Court::firstOrCreate(['name' => 'High Court of Delhi - Commercial Division'], [
            'court_category_id' => $commCat->id,
            'country_id' => $countries['IN']->id,
            'state_id' => $inStateDelhi->id,
            'city_id' => $inCityDelhi->id,
            'bench' => 'Special Commercial Appellate Division',
            'room_number' => 'Courtroom 14',
            'location' => 'Shershah Road, New Delhi 110503',
            'phone' => '+91 11 2338 7240',
        ]);

        // ==========================================
        // 4. STATUTES & BARE ACTS
        // ==========================================
        $acts = [
            ['name' => 'Federal Rules of Civil Procedure (FRCP)', 'code' => 'FRCP', 'year' => 1938, 'description' => 'Rules governing civil procedure in United States District Courts.'],
            ['name' => 'Delaware General Corporation Law (DGCL)', 'code' => 'DGCL', 'year' => 1899, 'description' => 'Statutory cornerstone of US corporate governance, merger standards, and fiduciary duties.'],
            ['name' => 'UK Arbitration Act 1996', 'code' => 'UK-ARB', 'year' => 1996, 'description' => 'Framework for domestic and international arbitral proceedings in England and Wales.'],
            ['name' => 'DIFC Contract Law & Arbitration Law', 'code' => 'DIFC-LAW', 'year' => 2008, 'description' => 'Common-law commercial contract and arbitral framework of the Dubai International Financial Centre.'],
            ['name' => 'Singapore International Arbitration Act (IAA)', 'code' => 'SG-IAA', 'year' => 1994, 'description' => 'International arbitration framework adopting the UNCITRAL Model Law in Singapore.'],
            ['name' => 'Defend Trade Secrets Act (DTSA)', 'code' => 'DTSA', 'year' => 2016, 'description' => 'Federal civil remedy for misappropriation of trade secrets affecting interstate commerce.'],
            ['name' => 'Commercial Courts Act 2015 (India)', 'code' => 'CCA-IN', 'year' => 2015, 'description' => 'Specialized dispute resolution of commercial disputes in High Courts of India.'],
            ['name' => 'EU General Data Protection Regulation (GDPR)', 'code' => 'GDPR', 'year' => 2016, 'description' => 'Regulation on privacy and cross-border digital data processing across the EU.'],
        ];
        foreach ($acts as $act) {
            Act::updateOrCreate(['code' => $act['code']], $act);
        }

        // ==========================================
        // 5. LITIGATION STAGES
        // ==========================================
        $stages = [
            ['name' => 'Pre-Litigation & Legal Notice', 'order' => 1, 'color' => 'info'],
            ['name' => 'Complaint / Plaint Filing', 'order' => 2, 'color' => 'primary'],
            ['name' => 'Service of Summons & Appearance', 'order' => 3, 'color' => 'warning'],
            ['name' => 'Answer / Written Statement', 'order' => 4, 'color' => 'primary'],
            ['name' => 'Motion for Summary Judgment', 'order' => 5, 'color' => 'warning'],
            ['name' => 'Discovery & Depositions', 'order' => 6, 'color' => 'primary'],
            ['name' => 'Trial / Final Arguments', 'order' => 7, 'color' => 'danger'],
            ['name' => 'Disposed / Judgment & Decree', 'order' => 8, 'color' => 'success'],
            ['name' => 'Execution & Enforcement', 'order' => 9, 'color' => 'success'],
        ];
        foreach ($stages as $stage) {
            Stage::updateOrCreate(['name' => $stage['name']], $stage);
        }
        $stageTrial = Stage::where('name', 'Trial / Final Arguments')->first();
        $stageDiscovery = Stage::where('name', 'Discovery & Depositions')->first();
        $stageNotice = Stage::where('name', 'Pre-Litigation & Legal Notice')->first();
        $stageJudgment = Stage::where('name', 'Disposed / Judgment & Decree')->first();

        // ==========================================
        // 6. PRACTICE AREAS
        // ==========================================
        $categories = [
            ['name' => 'Corporate & Commercial Litigation', 'description' => 'Shareholder disputes, M&A breaches, director fiduciary liability.'],
            ['name' => 'International Arbitration & Cross-Border Disputes', 'description' => 'ICC, LCIA, SIAC, and DIFC-LCIA international arbitrations.'],
            ['name' => 'Intellectual Property & Technology Litigation', 'description' => 'Patent infringement, proprietary algorithm theft, trade secret protection.'],
            ['name' => 'Banking, Insolvency & Fiduciary Restructuring', 'description' => 'Cross-border asset recovery, sovereign debt restructuring, IOLTA matters.'],
            ['name' => 'White Collar Defense & Regulatory Enforcement', 'description' => 'DOJ, SEC, FCA, and DFSA regulatory inquiries and anti-corruption compliance.'],
        ];
        foreach ($categories as $cat) {
            CaseCategory::updateOrCreate(['name' => $cat['name']], $cat);
        }
        $catCorp = CaseCategory::where('name', 'Corporate & Commercial Litigation')->first();
        $catArb = CaseCategory::where('name', 'International Arbitration & Cross-Border Disputes')->first();
        $catIp = CaseCategory::where('name', 'Intellectual Property & Technology Litigation')->first();

        // ==========================================
        // 7. MULTI-ROLE USERS (Authentic Accounts with 'password')
        // ==========================================
        // Role 1: Super Admin / Managing Partner
        $adminUser = User::updateOrCreate(['email' => 'admin@lexvanguard.law'], [
            'name' => 'Eleanor Vance',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'phone' => '+1 (555) 100-2001',
            'specialization' => 'Managing Partner / Global Appellate & Supreme Court Counsel',
            'bar_registration_no' => 'NY-BAR-882104 / UK-SRA-99120',
            'hourly_rate' => 750.00,
            'is_active' => true,
        ]);

        // Role 2: Senior Partner / Practice Head
        $partnerUser = User::updateOrCreate(['email' => 'partner@lexvanguard.law'], [
            'name' => 'Marcus Stone, KC',
            'password' => Hash::make('password'),
            'role' => 'partner',
            'phone' => '+1 (555) 100-2002',
            'specialization' => 'Senior Partner / International Commercial Arbitration (SIAC & LCIA)',
            'bar_registration_no' => 'UK-BAR-KC-449102',
            'hourly_rate' => 600.00,
            'is_active' => true,
        ]);

        // Role 3: Senior Trial Associate / Lawyer
        $lawyerUser = User::updateOrCreate(['email' => 'lawyer@lexvanguard.law'], [
            'name' => 'Sarah Lin, Esq.',
            'password' => Hash::make('password'),
            'role' => 'lawyer',
            'phone' => '+1 (555) 100-2003',
            'specialization' => 'Senior Trial Associate / AI & High-Tech Trade Secrets',
            'bar_registration_no' => 'NY-BAR-950412 / DE-BAR-33291',
            'hourly_rate' => 400.00,
            'is_active' => true,
        ]);

        // Role 4: Senior Litigation Paralegal
        $paralegalUser = User::updateOrCreate(['email' => 'paralegal@lexvanguard.law'], [
            'name' => 'Julian Hayes',
            'password' => Hash::make('password'),
            'role' => 'paralegal',
            'phone' => '+1 (555) 100-2004',
            'specialization' => 'Lead Litigation Paralegal / eDiscovery & Evidence Custody',
            'bar_registration_no' => 'NALA-CP-77412',
            'hourly_rate' => 175.00,
            'is_active' => true,
        ]);

        // Role 5: Trust Accountant & Financial Comptroller
        $accountantUser = User::updateOrCreate(['email' => 'accountant@lexvanguard.law'], [
            'name' => 'Elena Rostova, CPA',
            'password' => Hash::make('password'),
            'role' => 'accountant',
            'phone' => '+1 (555) 100-2005',
            'specialization' => 'Director of Finance & Fiduciary IOLTA Trust Comptroller',
            'bar_registration_no' => 'AICPA-9932104',
            'hourly_rate' => 250.00,
            'is_active' => true,
        ]);

        // Role 6: Corporate Client (NovaTech Chief Legal Officer)
        $clientUser = User::updateOrCreate(['email' => 'client@lexvanguard.law'], [
            'name' => 'David Sterling (NovaTech CLO)',
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone' => '+1 (555) 999-4400',
            'specialization' => 'Client Representative / In-House Legal Counsel',
            'is_active' => true,
        ]);

        // ==========================================
        // 8. LAWYERS & STAFF PROFILES
        // ==========================================
        $leadLawyer = Lawyer::updateOrCreate(['email' => $adminUser->email], [
            'user_id' => $adminUser->id,
            'name' => 'Eleanor Vance',
            'mobile_no' => $adminUser->phone,
            'designation' => 'Managing Partner',
            'specialization' => 'Global Commercial Litigation & Appellate Advocacy',
            'bar_council_id' => 'NY-BAR-882104',
            'hourly_rate' => 750.00,
            'description' => 'Distinguished trial advocate with over 24 years leading cross-border corporate litigations.',
        ]);

        $seniorPartnerLawyer = Lawyer::updateOrCreate(['email' => $partnerUser->email], [
            'user_id' => $partnerUser->id,
            'name' => 'Marcus Stone, KC',
            'mobile_no' => $partnerUser->phone,
            'designation' => 'Senior Partner (Head of Arbitration)',
            'specialization' => 'International Commercial Arbitration & Cross-Border M&A',
            'bar_council_id' => 'UK-BAR-KC-449102',
            'hourly_rate' => 600.00,
            'description' => 'King’s Counsel specializing in complex commercial arbitration under ICC, SIAC, and LCIA rules.',
        ]);

        $associateLawyer = Lawyer::updateOrCreate(['email' => $lawyerUser->email], [
            'user_id' => $lawyerUser->id,
            'name' => 'Sarah Lin, Esq.',
            'mobile_no' => $lawyerUser->phone,
            'designation' => 'Senior Litigation Associate',
            'specialization' => 'IP, Trade Secret Misappropriation & Artificial Intelligence Law',
            'bar_council_id' => 'NY-BAR-950412',
            'hourly_rate' => 400.00,
            'description' => 'Specialist in rapid emergency injunctory relief and software copyright forensics.',
        ]);

        // Staff Profiles (Paralegal & Accountant)
        Staff::updateOrCreate(['user_id' => $paralegalUser->id], [
            'employee_id' => 'LEX-PL-001',
            'phone' => $paralegalUser->phone,
            'designation' => 'Senior Litigation Paralegal & Discovery Lead',
            'department' => 'Litigation Practice Support',
            'basic_salary' => 6500.00,
            'date_of_joining' => Carbon::now()->subYears(3),
            'bank_name' => 'Citibank N.A.',
            'bank_account_no' => 'CITI-98442190',
        ]);

        Staff::updateOrCreate(['user_id' => $accountantUser->id], [
            'employee_id' => 'LEX-ACC-001',
            'phone' => $accountantUser->phone,
            'designation' => 'Senior Trust Accountant & Billing Comptroller',
            'department' => 'Finance & Fiduciary Accounting',
            'basic_salary' => 7800.00,
            'date_of_joining' => Carbon::now()->subYears(4),
            'bank_name' => 'JPMorgan Chase',
            'bank_account_no' => 'JPMC-77192041',
        ]);

        Staff::updateOrCreate(['user_id' => $lawyerUser->id], [
            'employee_id' => 'LEX-ATT-002',
            'phone' => $lawyerUser->phone,
            'designation' => 'Senior Trial Associate',
            'department' => 'Intellectual Property Practice',
            'basic_salary' => 11500.00,
            'date_of_joining' => Carbon::now()->subYears(2),
            'bank_name' => 'Bank of America',
            'bank_account_no' => 'BOA-33104928',
        ]);

        // ==========================================
        // 9. CLIENTS & CONTACTS
        // ==========================================
        $corpCat = ClientCategory::firstOrCreate(['name' => 'Multinational Corporation (Fortune 500)']);
        $sovereignCat = ClientCategory::firstOrCreate(['name' => 'Sovereign Wealth / Institutional Entity']);
        $hnwCat = ClientCategory::firstOrCreate(['name' => 'High Net Worth Founder / Family Office']);

        $novaTech = Client::updateOrCreate(['name' => 'NovaTech Global Inc'], [
            'user_id' => $clientUser->id,
            'client_category_id' => $corpCat->id,
            'type' => 'corporate',
            'company_name' => 'NovaTech Global Technologies Inc.',
            'tax_vat_number' => 'US-EIN-1299481',
            'email' => 'legal@novatech.com',
            'mobile' => '+1 (555) 777-8899',
            'address' => '500 Technology Way, Silicon Precinct, NY',
            'country_id' => $countries['US']->id,
            'state_id' => $usStateNy->id,
            'city_id' => $usCityNyc->id,
        ]);

        $vanguardMaritime = Client::updateOrCreate(['name' => 'Vanguard Maritime & Logistics LLC'], [
            'client_category_id' => $corpCat->id,
            'type' => 'corporate',
            'company_name' => 'Vanguard Maritime Global Holdings',
            'tax_vat_number' => 'GB-VAT-7729104',
            'email' => 'generalcounsel@vanguardlogistics.com',
            'mobile' => '+44 20 7946 0912',
            'address' => '88 Canary Wharf Tower, London E14 5AA',
            'country_id' => $countries['GB']->id,
            'state_id' => $ukLondonState->id,
            'city_id' => $ukCityLondon->id,
        ]);

        $alFuttaimGulf = Client::updateOrCreate(['name' => 'Al-Mirqab Gulf Energy Investments Ltd'], [
            'client_category_id' => $sovereignCat->id,
            'type' => 'corporate',
            'company_name' => 'Al-Mirqab Energy & Infrastructure Holdings',
            'tax_vat_number' => 'AE-TRN-100294821',
            'email' => 'counsel@almirqabenergy.ae',
            'mobile' => '+971 4 330 9000',
            'address' => 'Level 38, Al-Fattan Currency House, DIFC, Dubai',
            'country_id' => $countries['AE']->id,
            'state_id' => $uaeDubaiState->id,
            'city_id' => $uaeCityDubai->id,
        ]);

        $singaporeSemi = Client::updateOrCreate(['name' => 'SingaMicro Semiconductor Pte Ltd'], [
            'client_category_id' => $corpCat->id,
            'type' => 'corporate',
            'company_name' => 'SingaMicro Advanced Silicon Foundry',
            'tax_vat_number' => 'SG-UEN-202019482M',
            'email' => 'legal.asia@singamicro.sg',
            'mobile' => '+65 6789 1234',
            'address' => '12 Marina Boulevard, Marina Bay Financial Centre, Singapore',
            'country_id' => $countries['SG']->id,
            'state_id' => $sgState->id,
            'city_id' => $sgCity->id,
        ]);

        // Expert Contacts
        $expertCat = ContactCategory::firstOrCreate(['name' => 'Forensic Accounting & Economic Damages']);
        Contact::firstOrCreate(['name' => 'Dr. Aris Thorne, CPA, CFE'], [
            'contact_category_id' => $expertCat->id,
            'organization' => 'Thorne Global Forensic Econometrics',
            'email' => 'aris@thorneforensics.com',
            'mobile' => '+1 (555) 444-2200',
            'address' => 'Wall Street Plaza, 88 Pine St, New York',
            'notes' => 'Forensic accounting expert witness specializing in business valuations and discounted cash flow lost profit models.',
        ]);

        // ==========================================
        // 10. REALISTIC MULTI-JURISDICTION CASES
        // ==========================================
        // Case 1: US SDNY Commercial Breach & IP
        $case1 = LegalCase::updateOrCreate(['case_no' => 'CS(COMM) 204/2026'], [
            'title' => 'NovaTech Global Inc v. CyberDyne Systems Corp',
            'cnr_number' => 'US-SDNY-2026-CV-00918',
            'file_no' => 'LF-2026-US-01',
            'year' => 2026,
            'currency' => 'USD',
            'client_id' => $novaTech->id,
            'client_role' => 'Plaintiff',
            'opposite_party_name' => 'CyberDyne Systems Corp',
            'opposite_advocate_name' => 'Gibson, Dunn & Crutcher LLP',
            'case_category_id' => $catIp->id,
            'stage_id' => $stageTrial->id,
            'court_id' => $sdnyCourt->id,
            'lead_lawyer_id' => $leadLawyer->id,
            'receiving_date' => Carbon::now()->subMonths(6),
            'filing_date' => Carbon::now()->subMonths(5),
            'hearing_date' => Carbon::now()->subDays(14),
            'next_hearing_date' => Carbon::today(), // On Cause List TODAY
            'case_charge' => 220000.00,
            'billing_type' => 'hourly',
            'status' => 'Hearing',
            'priority' => 'Urgent',
            'description' => 'Suit for emergency permanent injunction and $28.5M damages arising from breach of enterprise cloud license agreement and intentional misappropriation of proprietary neural compression algorithms.',
            'prayer' => 'Pass a decree for permanent injunction restraining defendant from deploying proprietary neural compression weights; award actual damages of $28,500,000 together with 12% statutory prejudgment interest.',
        ]);

        // Case 2: Delaware Court of Chancery (Hostile Takeover / Fiduciary Duty)
        $case2 = LegalCase::updateOrCreate(['case_no' => 'C.A. No. 2026-0391-JTL'], [
            'title' => 'In re Apex Vanguard Sovereign Holdings Takeover Litigation',
            'cnr_number' => 'DE-CHANCERY-2026-CA-0391',
            'file_no' => 'LF-2026-CHANCERY-02',
            'year' => 2026,
            'currency' => 'USD',
            'client_id' => $alFuttaimGulf->id,
            'client_role' => 'Petitioner',
            'opposite_party_name' => 'Board of Directors of Apex BioTech Corp',
            'opposite_advocate_name' => 'Wachtell, Lipton, Rosen & Katz',
            'case_category_id' => $catCorp->id,
            'stage_id' => $stageDiscovery->id,
            'court_id' => $chanceryCourt->id,
            'lead_lawyer_id' => $leadLawyer->id,
            'receiving_date' => Carbon::now()->subMonths(3),
            'filing_date' => Carbon::now()->subMonths(2),
            'next_hearing_date' => Carbon::tomorrow(), // On Cause List TOMORROW
            'case_charge' => 350000.00,
            'billing_type' => 'hourly',
            'status' => 'Hearing',
            'priority' => 'Urgent',
            'description' => 'Chancery lawsuit alleging breach of Unocal and Revlon fiduciary standards in adopting poison pill anti-takeover mechanism in a $1.2B cash merger.',
            'prayer' => 'Order preliminary and permanent mandatory injunction compelling board to dismantle poison pill rights plan; conduct fair and unconflicted auction process.',
        ]);

        // Case 3: London High Court of Justice (Commercial Court - Maritime Demurrage)
        $case3 = LegalCase::updateOrCreate(['case_no' => 'CL-2026-000412'], [
            'title' => 'Vanguard Maritime LLC v. Oceanic Freight Consortia Ltd',
            'cnr_number' => 'UK-EWHC-COMM-2026-412',
            'file_no' => 'LF-2026-LON-03',
            'year' => 2026,
            'currency' => 'GBP',
            'client_id' => $vanguardMaritime->id,
            'client_role' => 'Claimant',
            'opposite_party_name' => 'Oceanic Freight Consortia Ltd & Insurers',
            'opposite_advocate_name' => 'Clifford Chance LLP (London)',
            'case_category_id' => $catCorp->id,
            'stage_id' => $stageDiscovery->id,
            'court_id' => $londonCommCourt->id,
            'lead_lawyer_id' => $seniorPartnerLawyer->id,
            'receiving_date' => Carbon::now()->subMonths(4),
            'filing_date' => Carbon::now()->subMonths(3),
            'next_hearing_date' => Carbon::today()->addDays(5),
            'case_charge' => 180000.00,
            'billing_type' => 'hourly',
            'status' => 'Hearing',
            'priority' => 'High',
            'description' => 'Commercial Court trial regarding breach of charter party agreement, contested force majeure notice, and £8.4M unpaid demurrage bond.',
            'prayer' => 'Declaration of wrongful repudiation; order for immediate payment of £8,420,000 demurrage with compounding commercial interest under UK Senior Courts Act.',
        ]);

        // Case 4: Singapore International Commercial Court (SICC) / SIAC
        $case4 = LegalCase::updateOrCreate(['case_no' => 'SIC/OA 19/2026'], [
            'title' => 'SingaMicro Semiconductor v. Pacific Silicon Foundry B.V.',
            'cnr_number' => 'SG-SICC-2026-OA-019',
            'file_no' => 'LF-2026-SG-04',
            'year' => 2026,
            'currency' => 'SGD',
            'client_id' => $singaporeSemi->id,
            'client_role' => 'Plaintiff',
            'opposite_party_name' => 'Pacific Silicon Foundry B.V.',
            'opposite_advocate_name' => 'Allen & Gledhill LLP',
            'case_category_id' => $catArb->id,
            'stage_id' => $stageNotice->id,
            'court_id' => $siccCourt->id,
            'lead_lawyer_id' => $seniorPartnerLawyer->id,
            'receiving_date' => Carbon::now()->subMonths(1),
            'filing_date' => Carbon::now()->subDays(15),
            'next_hearing_date' => Carbon::today()->addDays(12),
            'case_charge' => 250000.00,
            'billing_type' => 'fixed',
            'status' => 'Filing',
            'priority' => 'High',
            'description' => 'Cross-border semiconductor wafer supply dispute with parallel emergency relief application under SIAC rules.',
            'prayer' => 'Specific performance of 3nm wafer allocation contract; injunction restraining redirection of fab capacity.',
        ]);

        // Case 5: Dubai DIFC Courts (Technology & Construction Division)
        $case5 = LegalCase::updateOrCreate(['case_no' => 'CFI 088/2026'], [
            'title' => 'Al-Mirqab Energy v. Emirates Cloud Infrastructure FZ-LLC',
            'cnr_number' => 'AE-DIFC-CFI-2026-088',
            'file_no' => 'LF-2026-DIFC-05',
            'year' => 2026,
            'currency' => 'AED',
            'client_id' => $alFuttaimGulf->id,
            'client_role' => 'Claimant',
            'opposite_party_name' => 'Emirates Cloud Infrastructure FZ-LLC',
            'opposite_advocate_name' => 'Al Tamimi & Company',
            'case_category_id' => $catCorp->id,
            'stage_id' => $stageJudgment->id,
            'court_id' => $difcCourt->id,
            'lead_lawyer_id' => $leadLawyer->id,
            'receiving_date' => Carbon::now()->subMonths(8),
            'filing_date' => Carbon::now()->subMonths(7),
            'case_charge' => 450000.00,
            'billing_type' => 'fixed',
            'status' => 'Decided',
            'priority' => 'Medium',
            'description' => 'Enforcement of DIFC Technology Court arbitral award concerning sovereign data center infrastructure rollout.',
            'prayer' => 'Recognition and enforcement of AED 16.5M final award under DIFC Court Law No. 10 of 2004.',
        ]);

        // ==========================================
        // 11. HEARINGS & JUDGMENTS
        // ==========================================
        HearingDate::updateOrCreate(['case_id' => $case1->id, 'date' => Carbon::today()], [
            'stage_id' => $stageTrial->id,
            'court_room' => 'Courtroom 12B',
            'judge_name' => 'Hon. Katherine Forrest, District Judge',
            'advocate_appeared' => 'Eleanor Vance (Lead Counsel) & Sarah Lin',
            'business_conducted' => 'Cross-examination of defense expert witness on source code diff logs completed. Final oral summation on liability and punitive damages fixed for 2:00 PM.',
            'next_date' => Carbon::today()->addDays(7),
            'action_required' => 'Submit bench memorandum and updated damage quantum calculations.',
            'status' => 'Scheduled',
        ]);

        HearingDate::updateOrCreate(['case_id' => $case2->id, 'date' => Carbon::tomorrow()], [
            'stage_id' => $stageDiscovery->id,
            'court_room' => 'Courtroom 3A (Wilmington)',
            'judge_name' => 'Hon. J. Travis Laster, Vice Chancellor',
            'advocate_appeared' => 'Eleanor Vance (Lead Counsel)',
            'business_conducted' => 'Hearing on Plaintiff motion to compel production of unredacted board committee meeting minutes and WhatsApp executive transcripts.',
            'next_date' => Carbon::tomorrow()->addDays(14),
            'action_required' => 'Draft proposed form of order granting motion to compel within 48 hours.',
            'status' => 'Scheduled',
        ]);

        Judgment::updateOrCreate(['case_id' => $case5->id], [
            'judgment_date' => Carbon::now()->subDays(20),
            'judge_name' => 'Justice Sir Jeremy Cooke',
            'disposition' => 'Fully Allowed',
            'order_summary' => 'DIFC Court of First Instance entered decree recognizing and executing arbitral award in full. Respondent directed to deposit AED 16,500,000 into court escrow within 14 days.',
            'full_order_text' => 'UPON HEARING Claimant counsel Eleanor Vance and Respondent counsel; AND UPON reading the submissions: IT IS ORDERED THAT the Final Arbitral Award dated 14 November 2025 is recognized as enforceable in the same manner as a judgment of the DIFC Courts. Respondent shall pay AED 16,500,000 plus interest at 9% p.a.',
            'is_appeal_recommended' => false,
        ]);

        // ==========================================
        // 12. SERVICES, TAXES & IOLTA BANK ACCOUNTS
        // ==========================================
        $srvTrial = Service::firstOrCreate(['code' => 'SR-APPEAR'], [
            'name' => 'Managing Partner Trial Court Representation',
            'default_rate' => 1500.00,
            'billing_unit' => 'appearance',
            'description' => 'Oral trial appearance, cross-examination, and appellate argument by Managing Partner.',
        ]);

        $srvPartner = Service::firstOrCreate(['code' => 'PTR-HOURLY'], [
            'name' => 'Senior Partner Commercial Advisory',
            'default_rate' => 600.00,
            'billing_unit' => 'hour',
            'description' => 'Strategic arbitration positioning and complex contract dispute advisory.',
        ]);

        $srvAssociate = Service::firstOrCreate(['code' => 'ASSOC-HOURLY'], [
            'name' => 'Trial Associate Research & Motion Drafting',
            'default_rate' => 400.00,
            'billing_unit' => 'hour',
            'description' => 'Drafting substantive pleadings, summary judgment briefs, and statutory notices.',
        ]);

        $srvParalegal = Service::firstOrCreate(['code' => 'PARA-EVID'], [
            'name' => 'Litigation Support & eDiscovery Forensics',
            'default_rate' => 175.00,
            'billing_unit' => 'hour',
            'description' => 'Electronic discovery indexation, witness deposition transcript summaries, exhibit bundle assembly.',
        ]);

        $taxUs = Tax::firstOrCreate(['name' => 'US Commercial Legal Sales / Service Tax'], ['rate' => 8.875]);
        $taxUk = Tax::firstOrCreate(['name' => 'UK Standard VAT (Legal Services)'], ['rate' => 20.00]);
        $taxUae = Tax::firstOrCreate(['name' => 'UAE Federal VAT (Commercial)'], ['rate' => 5.00]);

        $operatingAcc = BankAccount::updateOrCreate(['account_no' => 'CHK-882109481'], [
            'account_name' => 'LexVanguard Firm Operating Account (USD)',
            'account_type' => 'Operating Account',
            'bank_name' => 'JPMorgan Chase Bank, N.A. (New York)',
            'balance' => 580000.00,
            'currency' => 'USD',
        ]);

        $trustAccUs = BankAccount::updateOrCreate(['account_no' => 'IOLTA-99210041'], [
            'account_name' => 'Client Trust Account (IOLTA Fund - USD)',
            'account_type' => 'Client Trust Account (IOLTA)',
            'bank_name' => 'Citibank Commercial Escrow (New York)',
            'balance' => 1250000.00,
            'currency' => 'USD',
            'description' => 'Fiduciary client retainers and dispute settlement escrows held under strict Bar trust accounting rules.',
        ]);

        $trustAccUk = BankAccount::updateOrCreate(['account_no' => 'SRA-TRUST-4401'], [
            'account_name' => 'Client Trust Account (SRA Escrow - GBP)',
            'account_type' => 'Client Trust Account (IOLTA)',
            'bank_name' => 'Barclays Corporate Banking (London)',
            'balance' => 650000.00,
            'currency' => 'GBP',
            'description' => 'Solicitors Regulation Authority (SRA) client account for English High Court litigation.',
        ]);

        // ==========================================
        // 13. INVOICES, TIME ENTRIES & TRANSACTIONS
        // ==========================================
        $inv1 = Invoice::updateOrCreate(['invoice_no' => 'INV-2026-0001'], [
            'case_id' => $case1->id,
            'client_id' => $novaTech->id,
            'currency' => 'USD',
            'invoice_date' => Carbon::now()->subDays(12),
            'due_date' => Carbon::now()->addDays(18),
            'sub_total' => 24500.00,
            'net_total' => 24500.00,
            'tax_id' => $taxUs->id,
            'tax_rate' => 8.875,
            'tax_amount' => 2174.38,
            'grand_total' => 26674.38,
            'paid' => 15000.00,
            'due' => 11674.38,
            'payment_status' => 'partially_paid',
            'notes' => 'Direct wire payment to LexVanguard Chase Operating Account. Reference Invoice # INV-2026-0001.',
            'created_by' => $adminUser->id,
        ]);
        $inv1->items()->delete();
        $inv1->items()->createMany([
            ['service_id' => $srvTrial->id, 'description' => 'Managing Partner trial arguments & expert cross-examination (2 appearances)', 'qty' => 2, 'rate' => 1500.00, 'total' => 3000.00],
            ['service_id' => $srvAssociate->id, 'description' => 'Trial Associate preparation of motion in limine & patent claim chart exhibits', 'qty' => 45, 'rate' => 400.00, 'total' => 18000.00],
            ['service_id' => $srvParalegal->id, 'description' => 'Paralegal discovery review of 12,000 adverse Git commit logs and transcript index', 'qty' => 20, 'rate' => 175.00, 'total' => 3500.00],
        ]);

        $inv2 = Invoice::updateOrCreate(['invoice_no' => 'INV-2026-0002'], [
            'case_id' => $case3->id,
            'client_id' => $vanguardMaritime->id,
            'currency' => 'GBP',
            'invoice_date' => Carbon::now()->subDays(5),
            'due_date' => Carbon::now()->addDays(25),
            'sub_total' => 18400.00,
            'net_total' => 18400.00,
            'tax_id' => $taxUk->id,
            'tax_rate' => 20.00,
            'tax_amount' => 3680.00,
            'grand_total' => 22080.00,
            'paid' => 22080.00,
            'due' => 0.00,
            'payment_status' => 'paid',
            'notes' => 'Settled via Barclays SRA client transfer. Received in full.',
            'created_by' => $partnerUser->id,
        ]);
        $inv2->items()->delete();
        $inv2->items()->createMany([
            ['service_id' => $srvPartner->id, 'description' => 'Senior Partner advisory on English High Court maritime arbitration stay motion', 'qty' => 24, 'rate' => 600.00, 'total' => 14400.00],
            ['service_id' => $srvParalegal->id, 'description' => 'Paralegal bundle preparation for Rolls Building Commercial Court Registrar', 'qty' => 22.85, 'rate' => 175.00, 'total' => 4000.00],
        ]);

        // Billable Time Entries
        TimeEntry::updateOrCreate(['case_id' => $case1->id, 'user_id' => $partnerUser->id, 'date' => Carbon::now()->subDays(2)], [
            'hours' => 5.0,
            'hourly_rate' => 600.00,
            'billable_amount' => 3000.00,
            'is_billed' => true,
            'invoice_id' => $inv1->id,
            'description' => 'Deposition preparation and cross-examination question formulation for CyberDyne Chief Software Architect.',
        ]);

        TimeEntry::updateOrCreate(['case_id' => $case1->id, 'user_id' => $lawyerUser->id, 'date' => Carbon::now()->subDays(1)], [
            'hours' => 6.5,
            'hourly_rate' => 400.00,
            'billable_amount' => 2600.00,
            'is_billed' => false,
            'description' => 'Drafting sur-reply on Defendant renewed motion for protective order and confidentiality seal.',
        ]);

        TimeEntry::updateOrCreate(['case_id' => $case2->id, 'user_id' => $paralegalUser->id, 'date' => Carbon::today()], [
            'hours' => 4.0,
            'hourly_rate' => 175.00,
            'billable_amount' => 700.00,
            'is_billed' => false,
            'description' => 'Assembled joint exhibit book for Delaware Chancery Court preliminary injunction hearing.',
        ]);

        // Transactions (IOLTA Trust Retainer & Fee Disbursement)
        Transaction::updateOrCreate(['reference_no' => 'TXN-TRUST-2026-01'], [
            'title' => 'Client Trust Retainer Deposit',
            'bank_account_id' => $trustAccUs->id,
            'invoice_id' => $inv1->id,
            'case_id' => $case1->id,
            'type' => 'in',
            'amount' => 50000.00,
            'transaction_date' => Carbon::now()->subDays(20),
            'payment_method' => 'bank_transfer',
            'description' => 'Advance litigation escrow deposit received from NovaTech Global Inc for SDNY trial defense.',
            'created_by' => $adminUser->id,
        ]);

        Transaction::updateOrCreate(['reference_no' => 'TXN-TRUST-2026-02'], [
            'title' => 'Earned Legal Fee Transfer to Operating',
            'bank_account_id' => $trustAccUs->id,
            'invoice_id' => $inv1->id,
            'case_id' => $case1->id,
            'type' => 'out',
            'amount' => 15000.00,
            'transaction_date' => Carbon::now()->subDays(10),
            'payment_method' => 'trust_transfer',
            'description' => 'Transfer from IOLTA Trust Account to LexVanguard Operating Account upon approval of Invoice # INV-2026-0001.',
            'created_by' => $accountantUser->id,
        ]);

        // ==========================================
        // 14. TASKS ACROSS EACH ROLE
        // ==========================================
        Task::updateOrCreate(['title' => 'Submit Joint Pre-Trial Order and Evidentiary Exhibits'], [
            'case_id' => $case1->id,
            'stage_id' => $stageTrial->id,
            'assigned_to' => $lawyerUser->id,
            'created_by' => $adminUser->id,
            'due_date' => Carbon::today()->addDays(2),
            'priority' => 'Urgent',
            'progress' => 90,
            'status' => 'in_progress',
            'description' => 'Coordinate with opposing counsel (Gibson Dunn) to certify joint exhibit list and eliminate duplicative witness testimony.',
        ]);

        Task::updateOrCreate(['title' => 'Prepare Chancery Discovery Subpoena Duces Tecum'], [
            'case_id' => $case2->id,
            'stage_id' => $stageDiscovery->id,
            'assigned_to' => $paralegalUser->id,
            'created_by' => $adminUser->id,
            'due_date' => Carbon::today()->addDays(3),
            'priority' => 'High',
            'progress' => 60,
            'status' => 'in_progress',
            'description' => 'Serve subpoena duces tecum upon Goldman Sachs investment banking advisors for board pitch decks.',
        ]);

        Task::updateOrCreate(['title' => 'Monthly IOLTA Fiduciary Trust Reconciliations (Oct 2026)'], [
            'assigned_to' => $accountantUser->id,
            'created_by' => $adminUser->id,
            'due_date' => Carbon::today()->addDays(5),
            'priority' => 'Urgent',
            'progress' => 75,
            'status' => 'in_progress',
            'description' => 'Complete three-way bank reconciliation between bank statements, client ledgers, and firm operating transfers to guarantee Bar trust compliance.',
        ]);

        // ==========================================
        // 15. HR, ATTENDANCE, LEAVE & PAYROLLS
        // ==========================================
        $leaveAnnual = LeaveType::firstOrCreate(['name' => 'Annual Professional Leave'], ['days_allowed' => 18, 'description' => 'Paid vacation and personal sabbatical leave']);
        $leaveSick = LeaveType::firstOrCreate(['name' => 'Medical / Sick Leave'], ['days_allowed' => 12, 'description' => 'Health and emergency recovery']);
        $leaveRecess = LeaveType::firstOrCreate(['name' => 'Judicial Court Recess'], ['days_allowed' => 10, 'description' => 'Leave during official high court summer/winter recess']);

        // Daily Attendance for each staff member
        foreach ([$adminUser, $partnerUser, $lawyerUser, $paralegalUser, $accountantUser] as $u) {
            Attendance::updateOrCreate(['user_id' => $u->id, 'date' => Carbon::today()], [
                'status' => 'Present',
                'clock_in' => '08:45:00',
                'clock_out' => '19:15:00',
                'note' => 'Active on litigation trial preparation and client briefings.',
            ]);
            Attendance::updateOrCreate(['user_id' => $u->id, 'date' => Carbon::yesterday()], [
                'status' => 'Present',
                'clock_in' => '08:50:00',
                'clock_out' => '18:30:00',
                'note' => 'Standard business day.',
            ]);
        }

        // Leave Requests
        LeaveRequest::updateOrCreate(['user_id' => $paralegalUser->id, 'start_date' => Carbon::today()->addDays(20)], [
            'leave_type_id' => $leaveAnnual->id,
            'end_date' => Carbon::today()->addDays(24),
            'total_days' => 5.0,
            'reason' => 'Annual family vacation during court procedural window.',
            'status' => 'approved',
            'approved_by' => $adminUser->id,
        ]);

        LeaveRequest::updateOrCreate(['user_id' => $lawyerUser->id, 'start_date' => Carbon::today()->addDays(35)], [
            'leave_type_id' => $leaveRecess->id,
            'end_date' => Carbon::today()->addDays(38),
            'total_days' => 4.0,
            'reason' => 'Continuing Legal Education (CLE) International Arbitration Symposium in Zurich.',
            'status' => 'pending',
        ]);

        // Payroll Slips
        $staffMembers = Staff::all();
        foreach ($staffMembers as $staff) {
            $bonus = 1500.00;
            $taxDeduction = $staff->basic_salary * 0.15;
            $gross = $staff->basic_salary + $bonus;
            $net = $gross - $taxDeduction;

            Payroll::updateOrCreate([
                'staff_id' => $staff->id,
                'month' => 'September',
                'year' => 2026,
            ], [
                'basic_salary' => $staff->basic_salary,
                'earnings' => $bonus,
                'deductions' => $taxDeduction,
                'gross_salary' => $gross,
                'net_salary' => $net,
                'status' => 'paid',
                'payment_date' => Carbon::now()->subDays(6),
                'payment_mode' => 'direct_deposit',
                'transaction_reference' => 'SAL-2026-09-' . $staff->id,
                'notes' => 'Salary and Q3 Litigation Performance Bonus disbursed via Chase Direct Deposit.',
            ]);
        }

        // ==========================================
        // 16. APPOINTMENTS & STRATEGY BRIEFINGS
        // ==========================================
        Appointment::updateOrCreate(['title' => 'NovaTech Pre-Verdict Settlement Conference'], [
            'client_id' => $novaTech->id,
            'lawyer_user_id' => $adminUser->id,
            'appointment_date' => Carbon::today()->addDays(3)->setHour(14)->setMinute(0),
            'motive' => 'Evaluate settlement proposal received from Gibson Dunn ($21M structured settlement over 24 months).',
            'location_or_link' => 'LexVanguard Executive Boardroom 1 / Secure Microsoft Teams',
            'status' => 'scheduled',
        ]);

        Appointment::updateOrCreate(['title' => 'SingaMicro SIAC Arbitration Strategy Session'], [
            'client_id' => $singaporeSemi->id,
            'lawyer_user_id' => $partnerUser->id,
            'appointment_date' => Carbon::today()->addDays(4)->setHour(10)->setMinute(30),
            'motive' => 'Finalize emergency arbitrator appointment request under SIAC 2024 Expedited Rules.',
            'location_or_link' => 'Marina Bay Financial Centre / LexVanguard Singapore Office',
            'status' => 'scheduled',
        ]);

        // ==========================================
        // 17. CONFLICT OF INTEREST INTELLIGENCE
        // ==========================================
        ConflictCheck::updateOrCreate(['party_name' => 'CyberDyne Systems Corp'], [
            'matter_description' => 'Prospective representation inquiry from third-party vendor relating to semiconductor patent licensing.',
            'searched_by' => $adminUser->id,
            'verdict' => 'Direct Conflict',
            'notes' => 'STRICT CONFLICT: LexVanguard actively represents NovaTech Global Inc against CyberDyne Systems Corp in US SDNY CS(COMM) 204/2026. Cannot accept adverse party representation under ABA Model Rule 1.7.',
        ]);

        ConflictCheck::updateOrCreate(['party_name' => 'Tokyo Electron Fabrication Ltd'], [
            'matter_description' => 'Commercial joint venture advisory on clean room equipment procurement in Singapore.',
            'searched_by' => $partnerUser->id,
            'verdict' => 'Cleared - No Conflict',
            'notes' => 'CLEARED: No active or past representations found across US, UK, UAE, or Singapore offices. Engagement authorized.',
        ]);
    }
}
