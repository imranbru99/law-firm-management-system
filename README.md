# 🏛️ LexVanguard • Global 2027 AI Law Firm Operating System

![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Filament v5](https://img.shields.io/badge/Filament-5.x-F59E0B?style=for-the-badge&logo=filament&logoColor=black)
![PHP 8.3](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Docker Ready](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![MariaDB 11](https://img.shields.io/badge/MariaDB-11.x-003545?style=for-the-badge&logo=mariadb&logoColor=white)
![Redis](https://img.shields.io/badge/Redis-Alpine-DC382D?style=for-the-badge&logo=redis&logoColor=white)
![Gemini AI](https://img.shields.io/badge/Google%20Gemini-1.5%20Pro-8E75C2?style=for-the-badge&logo=google&logoColor=white)

**LexVanguard** is an enterprise-grade, multi-jurisdictional digital Law Firm Management System and Autonomous Judicial Practice Operating System designed for modern law practices. 

Built with **Laravel 12**, **Filament v5**, and **Docker**, it incorporates every core workflow from legacy practice management applications while adding **2027 AI Copilot judicial intelligence**, **worldwide jurisdiction & currency support**, **IOLTA fiduciary trust accounting**, **airport-style live cause list boards**, **role-based workflows**, and an **obsidian & gold design system**.

---

## 📑 Table of Contents
1. [Executive Highlights](#-executive-highlights)
2. [Global Multi-Country & Jurisdiction Architecture](#-global-multi-country--jurisdiction-architecture)
3. [2027 AI Judicial Intelligence Suite](#-2027-ai-judicial-intelligence-suite)
4. [Role-Based Access Control & Seeded Credentials](#-role-based-access-control--seeded-credentials)
5. [Complete Module & Capability Matrix](#-complete-module--capability-matrix)
6. [Docker Deployment (Quick Start)](#-docker-deployment-quick-start)
7. [Local Development (Without Docker)](#-local-development-without-docker)
8. [Database Schema & Migrations Structure](#-database-schema--migrations-structure)
9. [Pre-Seeded High-Stakes Litigation Dataset](#-pre-seeded-high-stakes-litigation-dataset)
10. [Design System & Aesthetics](#-design-system--aesthetics)
11. [Configuration & Environment Variables](#-configuration--environment-variables)

---

## ⚡ Executive Highlights

* **🌍 16 Global Jurisdictions Pre-Seeded**: Full multi-country support covering US Federal & Delaware Chancery, UK Commercial Court, Dubai DIFC Courts, Singapore SICC/SIAC, India Commercial Courts, Australia, Canada, EU, and international arbitration tribunals.
* **🤖 2027 Autonomous AI Copilot**: Multi-mode legal intelligence featuring statutory research, witness impeachment cross-examination matrix, trial objections bench guide, and landmark precedent synthesis.
* **💳 IOLTA Fiduciary Trust Accounting**: Complete segregation of client retainers and settlement escrows from firm operating funds, compliant with Bar Association trust accounting standards.
* **📺 Airport-Style Cause List Board**: Live courtroom hearing dashboard for today's and upcoming listed matters with judge, bench, and counsel presence tracking.
* **👑 6 Dedicated Pre-Seeded Roles**: Super Admin / Managing Partner, Senior Partner, Trial Lawyer, Lead Paralegal, Trust Accountant, and Corporate Client with a 1-click credential switcher on `/admin/login`.
* **🎨 Ultra-Premium Obsidian & Gold Aesthetics**: Luxury editorial styling with dark mode glassmorphism, custom typography (Plus Jakarta Sans, Cinzel serif, JetBrains Mono), and gold glow accents.

---

## 🌐 Global Multi-Country & Jurisdiction Architecture

LexVanguard supports international law practices managing cross-border litigation and multi-currency retainers.

### Pre-Configured Global Jurisdictions

| Flag | Jurisdiction | Code | Dialing | Currency | Symbol | Primary Judicial Forum |
| :---: | :--- | :---: | :---: | :---: | :---: | :--- |
| 🇺🇸 | **United States** | `US` | `+1` | `USD` | `$` | Delaware Chancery Court & SDNY |
| 🇬🇧 | **United Kingdom** | `GB` | `+44` | `GBP` | `£` | High Court of Justice (Commercial Court, Rolls Bldg) |
| 🇦🇪 | **United Arab Emirates** | `AE` | `+971` | `AED` | `د.إ` | Dubai International Financial Centre (DIFC Courts) |
| 🇸🇬 | **Singapore** | `SG` | `+65` | `SGD` | `S$` | Singapore International Commercial Court (SICC / SIAC) |
| 🇮🇳 | **India** | `IN` | `+91` | `INR` | `₹` | Supreme Court of India & High Court Commercial Division |
| 🇨🇦 | **Canada** | `CA` | `+1` | `CAD` | `CA$` | Ontario Superior Court of Justice (Commercial List) |
| 🇦🇺 | **Australia** | `AU` | `+61` | `AUD` | `A$` | Federal Court of Australia & NSW Commercial List |
| 🇩🇪 | **Germany** | `DE` | `+49` | `EUR` | `€` | Landgericht Frankfurt (Commercial Division) |
| 🇫🇷 | **France** | `FR` | `+33` | `EUR` | `€` | Paris International Commercial Court (ICCP-CA) |
| 🇨🇭 | **Switzerland** | `CH` | `+41` | `CHF` | `CHF` | Swiss Federal Supreme Court & Geneva Arbitration |
| 🇯🇵 | **Japan** | `JP` | `+81` | `JPY` | `¥` | Tokyo District Court (Commercial Division) |
| 🇸🇦 | **Saudi Arabia** | `SA` | `+966` | `SAR` | `ر.س` | Commercial Courts of Riyadh & SCCA |
| 🇭🇰 | **Hong Kong SAR** | `HK` | `+852` | `HKD` | `HK$` | High Court of Hong Kong & HKIAC |
| 🇳🇱 | **Netherlands** | `NL` | `+31` | `EUR` | `€` | Netherlands Commercial Court (NCC) |
| 🇧🇷 | **Brazil** | `BR` | `+55` | `BRL` | `R$` | São Paulo State Business Law Court |
| 🇿🇦 | **South Africa** | `ZA` | `+27` | `ZAR` | `R` | High Court Commercial Court Division (Gauteng) |

### Jurisdiction Management (`CountryResource`)
* View, edit, and create custom countries, currency codes, ISO symbols, and regions under **Statutes & Jurisdiction &rarr; Countries & Currencies**.
* Associated hierarchical relationships: `Country &rarr; State / Division &rarr; City &rarr; Courts`.

---

## 🤖 2027 AI Judicial Intelligence Suite

LexVanguard includes an AI engine that works immediately via **built-in legal heuristics** or with **Google Gemini 1.5 Pro / Flash** when an API key is provided.

```
                    ┌──────────────────────────────────────────────┐
                    │      LexVanguard 2027 Legal AI Engine        │
                    └──────────────────────┬───────────────────────┘
                                           │
           ┌───────────────────────────────┼───────────────────────────────┐
           ▼                               ▼                               ▼
┌───────────────────────┐       ┌───────────────────────┐       ┌───────────────────────┐
│     AI Copilot        │       │   Document Drafter    │       │ Contract Risk Scanner │
│  • Legal Research     │       │  • Legal Notices      │       │  • Clause Risk Score  │
│  • Cross-Exam Matrix  │       │  • Bail Applications  │       │  • Uncapped Liability │
│  • Objections Guide   │       │  • Commercial NDAs    │       │  • Indemnity Exposure │
│  • Precedent Search   │       │  • Injunction Briefs  │       │  • Redline Proposals  │
└───────────────────────┘       └───────────────────────┘       └───────────────────────┘
```

### 1. Interactive Legal Copilot (`admin/ai-copilot-page`)
* **🏛️ Legal Research & Case Law**: In-depth statutory memos and jurisprudential analysis across 9 international forums.
* **⚔️ Cross-Examination Witness Impeachment Matrix**: Tailors question sequences designed to impeach adverse witnesses based on their stated theories and credentials.
* **📚 Landmark Precedent Search**: Formulates binding and persuasive case law citations with factual ratios and counter-arguments.
* **🛡️ Trial Objections Bench Guide**: In-court evidentiary objection formulations (Hearsay, Lack of Foundation, Compound Questions, Best Evidence Rule).
* **📋 Executive Case Brief Synthesizer**: Matter briefing and litigation roadmaps.

### 2. Generative Legal Document Drafter (`admin/ai-document-drafter-page`)
* Autonomous generation of formal **Legal Notices**, **Bail Applications (Sec 437/439 CrPC / Common Law)**, **Mutual NDAs**, **Demand Letters**, and **Commercial Injunction Petitions**.
* Automatic clause breakdown (Parties, Recitals, Operative Demands, Prayer / Relief, Verification).
* Direct persistence to the `ai_drafts` table with export and copy features.

### 3. Contract & Clause Risk Analyzer (`admin/ai-contract-risk-analyzer-page`)
* Deep clause scan calculating a **0–100 Risk Exposure Index**.
* Detects dangerous contractual provisions:
  * Uncapped / unlimited indemnification clauses.
  * Absence of aggregate liability caps.
  * Unilateral termination without cause.
  * Ambiguous governing law and non-neutral dispute forums.
* Generates counsel redlines and protective counter-clauses.

### 4. Ethical Conflict of Interest Scanner (`admin/conflict-checks`)
* Screen prospective representations across active litigants, past corporate clients, opposing counsels, and companion disputes.
* Generates Bar-compliant ethical audit logs under ABA Model Rule 1.7 / SRA rules.

---

## 👥 Role-Based Access Control & Seeded Credentials

Every role has an active account with dedicated permissions, billable rates, and operational responsibilities.

> [!IMPORTANT]
> **Universal Password:** The password for all accounts is **`password`**.  
> **1-Click Login:** Visit [http://localhost:8080/admin/login](http://localhost:8080/admin/login) to use the 1-click credential selector.

| Role | Name & Title | Seeded Email | Password | Billing Rate | Primary Responsibilities |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **👑 Super Admin** | Eleanor Vance | `admin@lexvanguard.law` | `password` | $750.00 / hr | Managing Partner, Global Head of Litigation, Practice Administration |
| **⚖️ Senior Partner** | Marcus Stone, KC | `partner@lexvanguard.law` | `password` | $600.00 / hr | Head of International Commercial Arbitration (SIAC & LCIA) |
| **🏛️ Trial Lawyer** | Sarah Lin, Esq. | `lawyer@lexvanguard.law` | `password` | $400.00 / hr | Senior Trial Associate, IP & High-Tech Trade Secret Litigation |
| **📁 Paralegal** | Julian Hayes | `paralegal@lexvanguard.law` | `password` | $175.00 / hr | Lead Litigation Paralegal, eDiscovery, Trial Bundle Preparation |
| **💳 Accountant** | Elena Rostova, CPA | `accountant@lexvanguard.law` | `password` | $250.00 / hr | Director of Finance, Fiduciary IOLTA Trust Accounting, Payroll |
| **🏢 Corporate Client** | David Sterling | `client@lexvanguard.law` | `password` | Client Account | Chief Legal Officer, NovaTech Global Inc. (Client Transparency Portal) |

---

## 📦 Complete Module & Capability Matrix

### 1. Litigation & Court Lifecycle
* **Digital Case Dossier**: Complete docket tracking with **CNR Number**, Case Number, File Reference, Filing Year, Client Role (Plaintiff, Appellant, Respondent, Defendant, Third-Party).
* **Assigned Legal Team**: Lead Counsel, Briefing Counsel, Associate Advocates, and Paralegals.
* **Hearing Dates History**: Timed court proceedings log, advocate appearances, judge remarks, and next hearing date assignments.
* **Live Cause List Board (`admin/cause-list-daily-board-page`)**: Airport/Tribunal style live board for *Today's Hearings*, *Tomorrow's Board*, and *Weekly Listings*.
* **Judgments & Decrees**: Disposal records, ratio decidendi summaries, operative order texts, appeal recommendations, and PDF judgment archives.
* **Connected Matters**: Companion cases, cross-petitions, and linked appeals tracking.

### 2. Finance & Fiduciary Trust Accounting (IOLTA Compliant)
* **IOLTA Client Trust Accounts**: Fiduciary segregation of Client Trust Accounts (Retainers / Settlement Escrow) from Firm Operating Accounts in USD, GBP, EUR, AED, and SGD.
* **Invoicing & Billing**: Auto-numbered invoices (`INV-2026-XXXX`), line-item services catalog, hourly vs. fixed billing, tax calculations, discounts, and payment status badges.
* **One-Click PDF Invoices**: Clean, court-ready printable PDF tax invoices with full financial breakdown.
* **Ledger Audit Trail**: Comprehensive Money In / Money Out ledger entries with UTR and payment references.
* **Billable Time Tracking**: Billable hours recorded per matter with rate multipliers and invoice billing flags.

### 3. Clients & Contacts Directory
* **Client Portfolio**: Corporate enterprises, sovereign wealth funds, and private clients with tax IDs, multi-jurisdiction addresses, and KYC records.
* **Legal Contacts & Experts**: Forensic accounting expert witnesses, process servers, and opposing counsel directory.
* **Consultations & Appointments**: Scheduling with MS Teams/Zoom/Boardroom location links.

### 4. Practice Operations & HR
* **Counsel & Advocate Profiles**: Bar registration numbers, court enrollments, designations, specializations, and billing rates.
* **Staff Directory**: Paralegals, administrative clerks, accountants, salary structures, and bank credentials.
* **Daily Attendance**: Punch-in/out logging, monthly attendance calendar, presence statuses.
* **Leave Management**: Leave requests, casual/medical/court vacation quotas, partner approval workflows.
* **Staff Payroll**: Monthly salary slip generation, gross/net compensation, deductions, and payment vouchers.
* **Litigation Tasks**: Priority-tagged action items tied directly to legal matters and procedural stages.

### 5. Statutes & Practice Tools
* **Bare Acts & Statutes**: Substantive statutes repository (FRCP, DGCL, UK Arbitration Act, DIFC Law, Singapore IAA, DTSA, GDPR).
* **Litigation Stages Pipeline**: Notice &rarr; Filing &rarr; Summons &rarr; Written Statement &rarr; Discovery &rarr; Trial &rarr; Decree &rarr; Execution.
* **Ad-Valorem Court Fee Calculator**: Statutory court fee calculator applying tiered jurisdiction schedules based on suit valuation.
* **Decree Interest Calculator**: Sec 34 CPC / Commercial interest calculator (Simple and Compound quarterly interest on decreetal awards).

---

## 🐳 Docker Deployment (Quick Start)

The application includes a production-ready containerized stack with **PHP 8.3-FPM**, **Nginx**, **MariaDB 11**, and **Redis**.

### 1. Launch with Docker Compose

Open terminal in the project directory:

```bash
docker compose up --build -d
```

### 2. Automated Container Startup Flow
1. **MariaDB 11 (`law_firm_db`)**: Starts on port `3307` and runs health checks.
2. **Redis Alpine (`law_firm_redis`)**: Starts on port `6380`.
3. **PHP 8.3-FPM (`law_firm_app`)**: 
   - Fixes volume permissions (`777` on `storage/` and `bootstrap/cache/`).
   - Executes database migrations (`php artisan migrate --force`).
   - Seeds the multi-country litigation dataset (`php artisan db:seed --force`).
   - Generates storage symlinks and clears config caches.
4. **Nginx Web (`law_firm_web`)**: Serves HTTP traffic on port `8080`.

### 3. Access URLs

* **Public Portal**: [http://localhost:8080](http://localhost:8080)
* **Management Console**: [http://localhost:8080/admin](http://localhost:8080/admin)
* **2027 AI Copilot**: [http://localhost:8080/admin/ai-copilot-page](http://localhost:8080/admin/ai-copilot-page)
* **Live Cause List Board**: [http://localhost:8080/admin/cause-list-daily-board-page](http://localhost:8080/admin/cause-list-daily-board-page)

---

## 🛠️ Local Development (Without Docker)

You can run the application directly on your local machine using PHP 8.3 and SQLite/MySQL:

```bash
# 1. Install Composer dependencies
composer install

# 2. Setup environment file
cp .env.example .env
php artisan key:generate

# 3. Run database migrations with comprehensive seed data
php artisan migrate:fresh --seed

# 4. Start local development server
php artisan serve
```

Access locally at `http://localhost:8000/admin`.

---

## 🗄️ Database Schema & Migrations Structure

The database architecture is partitioned into 7 migration domains:

```
database/migrations/
├── 2026_10_07_000001_create_core_master_tables.php
│   ├── firm_settings (firm name, logo, currency, AI model, VAT)
│   ├── countries (code, phonecode, currency, symbol, flag, region)
│   ├── states & cities
│   ├── court_categories & courts (bench, courtroom, location)
│   ├── acts (statutes, Bare Acts, citation codes, years)
│   └── stages (litigation pipeline order, status badges)
│
├── 2026_10_07_000002_create_clients_and_contacts_tables.php
│   ├── client_categories & clients (corporate/individual, tax ID, KYC)
│   ├── contact_categories & contacts (expert witnesses, opposing counsel)
│   └── appointments (client strategy sessions, video links)
│
├── 2026_10_07_000003_create_lawyers_and_staff_tables.php
│   ├── lawyers (bar registration, designation, hourly rate)
│   ├── staff (employee ID, department, salary, banking details)
│   └── documents (confidential uploads, pleadings)
│
├── 2026_10_07_000004_create_cases_and_litigation_tables.php
│   ├── cases (case_no, CNR, title, prayer, billing_type, priority)
│   ├── case_lawyer & case_acts (pivot tables)
│   ├── hearing_dates (daily listings, judge, business conducted)
│   ├── judgments (operative orders, decree text, appeal flag)
│   └── case_notes & connected_matters
│
├── 2026_10_07_000005_create_finance_and_trust_tables.php
│   ├── services (legal fees catalog, billing units)
│   ├── taxes (VAT, GST, sales tax)
│   ├── bank_accounts (fiduciary IOLTA trust vs firm operating)
│   ├── invoices & invoice_items (itemized fee billing, auto-numbering)
│   ├── transactions (IOLTA trust retainers & disbursements ledger)
│   └── time_entries (billable hours tracking per matter)
│
├── 2026_10_07_000006_create_operations_and_hr_tables.php
│   ├── attendances (daily clock in/out tracking)
│   ├── leave_types & leave_requests (vacation, court recess)
│   ├── payrolls (monthly salary slips, bonuses, deductions)
│   └── tasks (procedural action items tied to cases)
│
└── 2026_10_07_000007_create_legal_tools_and_ai_tables.php
    ├── conflict_checks (ethical screening logs, verdicts)
    ├── court_fee_calculations & interest_calculations
    └── ai_drafts & ai_risk_analyses (AI document and contract records)
```

---

## 🏛️ Pre-Seeded High-Stakes Litigation Dataset

The application seeds 5 realistic legal matters:

1. **`CS(COMM) 204/2026`** — *NovaTech Global Inc v. CyberDyne Systems Corp*
   * **Forum**: U.S. District Court (SDNY) — Commercial Litigation Division
   * **Subject**: $28.5M Trade Secret Misappropriation & Neural Compression Algorithm Injunction
   * **Status**: **Listed on Today's Live Cause Board** for expert witness cross-examination.
2. **`C.A. No. 2026-0391-JTL`** — *In re Apex Vanguard Sovereign Holdings Takeover*
   * **Forum**: Delaware Court of Chancery (Wilmington)
   * **Subject**: Hostile Takeover Injunction & Revlon/Unocal Fiduciary Duty Claims ($1.2B merger)
   * **Status**: **Listed Tomorrow** on Plaintiff's Motion to Compel production of board minutes.
3. **`CL-2026-000412`** — *Vanguard Maritime LLC v. Oceanic Freight Consortia Ltd*
   * **Forum**: High Court of Justice (England & Wales) — Commercial Court (Rolls Building, London)
   * **Subject**: £8.4M Demurrage & Maritime Charter Party Repudiation Dispute
   * **Currency**: GBP (£)
4. **`SIC/OA 19/2026`** — *SingaMicro Semiconductor v. Pacific Silicon Foundry B.V.*
   * **Forum**: Singapore International Commercial Court (SICC) / SIAC
   * **Subject**: Cross-border 3nm semiconductor fab capacity specific performance.
   * **Currency**: SGD (S$)
5. **`CFI 088/2026`** — *Al-Mirqab Energy v. Emirates Cloud Infrastructure FZ-LLC*
   * **Forum**: Dubai International Financial Centre (DIFC Courts) — Technology & Construction Division
   * **Subject**: Execution and enforcement of AED 16.5M final commercial arbitral award.
   * **Status**: Decided / Decree entered in favor of Claimant.

---

## 🎨 Design System & Aesthetics

LexVanguard features an obsidian and gold design system tailored for law practices:

* **Color Palette**: Deep obsidian backdrop (`#090d16` / `#0d131f`) with warm amber & gold accents (`#d97706` / `#fbbf24`).
* **Typography**:
  * Headings: **Cinzel** (traditional legal serif).
  * Interface: **Plus Jakarta Sans** (clean modern UI).
  * Data & Citations: **JetBrains Mono** (statutory codes, CNR numbers, financial figures).
* **Glassmorphic Elevation**: Translucent cards with subtle border illumination (`backdrop-filter: blur(16px)`).
* **Live Cause List Board**: Real-time tribunal style display with responsive status badges and courtroom filters.
* **Custom Stylesheet**: Located at [`public/css/filament-custom.css`](file:///c:/docker/law-firm-management-system/public/css/filament-custom.css), automatically injected into Filament via `PanelsRenderHook::HEAD_END`.

---

## ⚙️ Configuration & Environment Variables

### Gemini AI Configuration (Optional)
To use live Google Gemini models instead of the built-in legal heuristic engine:

```env
# Google Gemini API Configuration
GEMINI_API_KEY=your_google_gemini_api_key_here
GEMINI_MODEL=gemini-1.5-pro
```

### Docker Ports Reference
If you run multiple local applications, ports are configured in `docker-compose.yml`:
* **Web (Nginx)**: `8080:80`
* **MariaDB Database**: `3307:3306`
* **Redis Cache**: `6380:6379`

### Maintenance Commands via Docker

```bash
# Re-run migrations and seeds inside Docker
docker exec law_firm_app php artisan migrate:fresh --seed

# Clear application caches
docker exec law_firm_app php artisan optimize:clear

# View application logs
docker compose logs -f app
```

---

## ⚖️ License & Fiduciary Notice
LexVanguard is released under the **MIT License**. All seeded case files, parties, and court documents are simulated for software demonstration and testing purposes.

---

## 🤝 Let's Connect & Collaborate

I am open to **Senior Remote Full-Stack Roles**, **AI Platform Architecture Contracts**, and **Enterprise Technical Advisory**.

* **Timezone**: UTC+6 (Dhaka / Rangpur, Bangladesh) — Flexible overlap with US, UK, and European business hours.
* **Delivery Mode**: Async-ready, Slack, Discord, Jira, GitHub, and production-first accountability.

| Channel | Address / Handle | Quick Action |
| :--- | :--- | :--- |
| 🌐 **Primary Portfolio** | [imrandev.bd](https://imrandev.bd/) | [Visit Site ↗](https://imrandev.bd/) |
| 📦 **Packagist Packages** | [packagist.org/packages/imrandevbd/](https://packagist.org/packages/imrandevbd/) | [View Packages ↗](https://packagist.org/packages/imrandevbd/) |
| 💼 **LinkedIn Profile** | [linkedin.com/in/imranbru99](https://linkedin.com/in/imranbru99) | [Connect ↗](https://linkedin.com/in/imranbru99) |
| 🐙 **GitHub Profile** | [github.com/imranbru99](https://github.com/imranbru99) | [Follow ↗](https://github.com/imranbru99) |
| 💬 **WhatsApp Direct** | [+880 1576-918420](http://wa.me/+8801576918420) | [Chat Now ↗](http://wa.me/+8801576918420) |
| 📧 **Direct Email** | [me@imrandev.bd](mailto:me@imrandev.bd) | [Send Email ↗](mailto:me@imrandev.bd) |
| 🐦 **X (Twitter)** | [@imrandev_bd](https://x.com/imrandev_bd) | [Follow ↗](https://x.com/imrandev_bd) |
| 📺 **YouTube Tech** | [@ImranDevBD](https://youtube.com/@ImranDevBD) | [Subscribe ↗](https://youtube.com/@ImranDevBD) |

⭐ **Star this repository if you find it valuable!**

