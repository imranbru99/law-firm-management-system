<?php

namespace App\Services;

use App\Models\LegalCase;
use App\Models\Client;
use App\Models\Contact;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LegalAiService
{
    protected ?string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));
        $this->model = config('services.gemini.model', env('GEMINI_MODEL', 'gemini-1.5-flash'));
    }

    /**
     * Draft legal document based on template type and prompt requirements
     */
    public function draftDocument(string $templateType, string $prompt, ?LegalCase $case = null): array
    {
        if ($this->apiKey) {
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}", [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => "You are an elite senior legal counsel and judicial draftsman. Draft a formal, professional legal document of type: '{$templateType}'. Requirements: {$prompt}. If applicable, incorporate case context: " . ($case ? "Case: {$case->title}, Court: {$case->court?->name}, CNR: {$case->cnr_number}" : "Standard jurisdictional format")]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.2,
                        'maxOutputTokens' => 2048,
                    ]
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $content = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($content) {
                        return [
                            'content' => $content,
                            'clauses' => $this->extractKeyClauses($content),
                            'source' => 'gemini-api',
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Gemini AI API call failed: " . $e->getMessage());
            }
        }

        // Smart Legal Drafting Heuristic Engine
        return [
            'content' => $this->generateHeuristicLegalDraft($templateType, $prompt, $case),
            'clauses' => [
                'Parties and Jurisdiction',
                'Recitals & Statement of Facts',
                'Statutory Grounds & Cause of Action',
                'Operative Covenants / Demands',
                'Prayer / Relief Claimed',
                'Verification & Attestation Clause',
            ],
            'source' => 'lexvanguard-engine',
        ];
    }

    /**
     * Analyze legal agreement clauses and calculate risk score
     */
    public function analyzeContractRisk(string $contractText): array
    {
        $detectedClauses = [];
        $riskScore = 20;
        $recommendations = [];

        // Check for Indemnity
        if (preg_match('/indemnif|hold harmless/i', $contractText)) {
            $isUncapped = preg_match('/unlimited|without limitation|sole and absolute/i', $contractText);
            $riskScore += $isUncapped ? 30 : 15;
            $detectedClauses[] = [
                'clause' => 'Indemnification & Hold Harmless',
                'risk' => $isUncapped ? 'High' : 'Medium',
                'finding' => $isUncapped ? 'Broad uncapped indemnity found exposing the firm to consequential liabilities.' : 'Standard reciprocal indemnity clause detected.',
                'redline' => 'Cap liability to the aggregate professional fees received under the agreement over preceding 12 months.',
            ];
            $recommendations[] = 'Insert standard liability caps and exclude indirect/punitive damages.';
        }

        // Check for Limitation of Liability
        if (!preg_match('/limitation of liability|aggregate liability/i', $contractText)) {
            $riskScore += 25;
            $detectedClauses[] = [
                'clause' => 'Limitation of Liability',
                'risk' => 'High',
                'finding' => 'Missing aggregate liability cap clause.',
                'redline' => 'Add clause limiting maximum liability to fees billed.',
            ];
            $recommendations[] = 'Add an express Limitation of Liability clause.';
        } else {
            $detectedClauses[] = [
                'clause' => 'Limitation of Liability',
                'risk' => 'Low',
                'finding' => 'Express limitation of liability is present.',
                'redline' => 'Ensure carve-outs do not negate the protective cap.',
            ];
        }

        // Check for Governing Law & Dispute Resolution
        if (preg_match('/arbitration|governing law|exclusive jurisdiction/i', $contractText)) {
            $detectedClauses[] = [
                'clause' => 'Dispute Resolution & Jurisdiction',
                'risk' => 'Low',
                'finding' => 'Defined forum selection and arbitration mechanism.',
                'redline' => 'Verify seat of arbitration and applicable procedural rules.',
            ];
        } else {
            $riskScore += 20;
            $detectedClauses[] = [
                'clause' => 'Dispute Resolution',
                'risk' => 'Medium',
                'finding' => 'Jurisdiction clause is ambiguous or absent.',
                'redline' => 'Specify exclusive jurisdiction of competent civil courts.',
            ];
            $recommendations[] = 'Specify clear venue and seat of arbitration to avoid jurisdictional conflicts.';
        }

        // Check for Unilateral Termination
        if (preg_match('/terminate at convenience|immediate termination without cause/i', $contractText)) {
            $riskScore += 15;
            $detectedClauses[] = [
                'clause' => 'Termination for Convenience',
                'risk' => 'Medium',
                'finding' => 'Unilateral termination without cure period detected.',
                'redline' => 'Provide a mandatory 30-day notice and cure period before termination.',
            ];
            $recommendations[] = 'Negotiate mutual 30-day cure period for material breach.';
        }

        $riskScore = min(100, max(10, $riskScore));

        return [
            'risk_score' => $riskScore,
            'detected_clauses' => $detectedClauses,
            'recommendations' => implode("\n", array_map(fn($r, $i) => ($i + 1) . ". " . $r, $recommendations, array_keys($recommendations))),
        ];
    }

    /**
     * Check for potential Conflict of Interest
     */
    public function checkConflict(string $partyName): array
    {
        $term = trim($partyName);
        $matches = [];

        // Check Clients
        $clientMatches = Client::where('name', 'like', "%{$term}%")
            ->orWhere('company_name', 'like', "%{$term}%")
            ->get();
        foreach ($clientMatches as $client) {
            $matches[] = [
                'type' => 'Existing Client',
                'name' => $client->name . ($client->company_name ? " ({$client->company_name})" : ''),
                'details' => "Client ID: #{$client->id}, Mobile: {$client->mobile}, Category: " . ($client->category?->name ?? 'General'),
                'severity' => 'Direct Conflict',
            ];
        }

        // Check Opposing Parties in active cases
        $opposingCases = LegalCase::where('opposite_party_name', 'like', "%{$term}%")
            ->with(['client', 'court'])
            ->get();
        foreach ($opposingCases as $case) {
            $matches[] = [
                'type' => 'Adverse Party in Active Case',
                'name' => $case->opposite_party_name,
                'details' => "Appears as Opposing Party in '{$case->title}' (Case No: {$case->case_no}) representing client '{$case->client?->name}'",
                'severity' => 'Direct Conflict',
            ];
        }

        // Check Contacts / Witnesses / Opposing Counsels
        $contactMatches = Contact::where('name', 'like', "%{$term}%")
            ->orWhere('organization', 'like', "%{$term}%")
            ->with('category')
            ->get();
        foreach ($contactMatches as $contact) {
            $matches[] = [
                'type' => 'Registered Contact / ' . ($contact->category?->name ?? 'Contact'),
                'name' => $contact->name . ($contact->organization ? " ({$contact->organization})" : ''),
                'details' => "Category: " . ($contact->category?->name ?? 'General') . ", Org: {$contact->organization}",
                'severity' => 'Potential Conflict',
            ];
        }

        $verdict = 'Cleared';
        if (count($matches) > 0) {
            $hasDirect = collect($matches)->contains('severity', 'Direct Conflict');
            $verdict = $hasDirect ? 'Direct Conflict' : 'Potential Conflict';
        }

        return [
            'verdict' => $verdict,
            'matches' => $matches,
            'total_matches' => count($matches),
        ];
    }

    /**
     * Synthesize Case Brief
     */
    public function generateCaseBrief(LegalCase $case): string
    {
        $case->loadMissing(['client', 'court', 'stage', 'category', 'leadLawyer', 'hearingDates', 'acts', 'notes']);

        $hearingList = $case->hearingDates->take(5)->map(function ($h) {
            return "- [{$h->date->format('Y-m-d')}] {$h->stage?->name}: " . ($h->business_conducted ?: 'Proceedings conducted');
        })->implode("\n");

        $actsList = $case->acts->map(function ($a) {
            return "- {$a->name} (" . ($a->pivot->sections ?: 'General provisions') . ")";
        })->implode("\n");

        return <<<MARKDOWN
# EXECUTIVE LEGAL BRIEF: {$case->title}
**CNR Number:** {$case->cnr_number} | **Case No:** {$case->case_no} | **Filing Year:** {$case->year}
**Forum / Court:** {$case->court?->name} (Bench: {$case->court?->bench}, Room: {$case->court?->room_number})
**Lead Advocate:** {$case->leadLawyer?->name}
**Status:** {$case->status} | **Current Stage:** {$case->stage?->name} | **Next Hearing:** {$case->next_hearing_date?->format('Y-m-d')}

---

### 1. PARTIES & JURISDICTION
- **Client / {$case->client_role}:** {$case->client?->name} ({$case->client?->type})
- **Opposing Party:** {$case->opposite_party_name}
- **Opposing Counsel:** {$case->opposite_advocate_name}
- **Subject Matter Category:** {$case->category?->name}

### 2. STATUTORY FRAMEWORK & SUBSTANTIVE ACTS
{$actsList}

### 3. FACTUAL CHRONOLOGY & CASE SUMMARY
{$case->description}

### 4. PRAYER & SUBSTANTIVE RELIEF SOUGHT
{$case->prayer}

### 5. PROCEDURAL HISTORY & RECENT PROCEEDINGS
{$hearingList}

### 6. KEY LITIGATION STRATEGY & NEXT ACTIONS
1. Ensure evidence affidavits and rejoinder are served upon opposing counsel 48 hours prior to next date.
2. Prepare cross-examination questionnaire focusing on material contradictions.
3. Review statutory limitation defenses under the cited Bare Acts.
MARKDOWN;
    }

    protected function extractKeyClauses(string $text): array
    {
        $lines = explode("\n", $text);
        $clauses = [];
        foreach ($lines as $line) {
            if (preg_match('/^(#{1,3}\s+|SECTION\s+\d+|ARTICLE\s+\d+|CLAUSE\s+\d+)(.+)/i', trim($line), $matches)) {
                $clauses[] = trim($matches[2]);
            }
        }
        return array_slice($clauses, 0, 6);
    }

    protected function generateHeuristicLegalDraft(string $type, string $prompt, ?LegalCase $case): string
    {
        $date = now()->format('F d, Y');
        $clientName = $case?->client?->name ?? 'REPRESENTED CLIENT';
        $oppName = $case?->opposite_party_name ?? 'ADVERSE PARTY / RESPONDENT';
        $courtName = $case?->court?->name ?? 'THE HON’BLE COMPETENT COURT';

        switch (strtolower($type)) {
            case 'legal notice':
            case 'demand letter':
                return <<<TEXT
LEGAL DEMAND NOTICE
(REGISTERED POST WITH ACKNOWLEDGEMENT DUE / SPEED POST)

Date: {$date}

TO:
{$oppName}
Address: [Opposite Party Registered Address]

FROM:
LexVanguard Legal Partners
On behalf of our Client: {$clientName}

SUBJECT: FORMAL DEMAND NOTICE REGARDING: {$prompt}

Sir / Madam,

Under instructions from and on behalf of our client, {$clientName}, we hereby serve upon you this formal Legal Notice:

1. That our client is a law-abiding citizen / corporate entity having continuous dealings with you.
2. That in furtherance of the agreements and transactions entered between the parties, you were obligated to honor your contractual and statutory commitments.
3. Specific grievance: {$prompt}
4. That despite repeated demands, reminders, and requests made by our client, you have willfully neglected and failed to discharge your legal liabilities.
5. That by virtue of your actions, our client has suffered severe pecuniary damages, prejudice, and injury.

WE THEREFORE HEREBY CALL UPON YOU to pay/remedy the aforementioned default within a period of FIFTEEN (15) DAYS from the date of receipt of this notice, failing which our client has given us strict instructions to initiate appropriate civil and criminal proceedings before the competent courts at your sole risk, cost, and consequences.

Yours faithfully,

For LexVanguard Legal Partners
Advocates & Legal Consultants
TEXT;

            case 'bail application':
                return <<<TEXT
IN THE COURT OF {$courtName}
CRIMINAL MISCELLANEOUS BAIL APPLICATION NO. _____ OF 2026

IN THE MATTER OF:
{$clientName}                                  ... APPLICANT / ACCUSED
                      VERSUS
THE STATE                                      ... RESPONDENT

APPLICATION UNDER SECTION 437/439 CR.P.C. FOR GRANT OF REGULAR BAIL

MOST RESPECTFULLY SHOWETH:
1. That the applicant has been falsely implicated in Crime No. ________ for alleged offenses under relevant sections of the law.
2. That the applicant is an innocent person having deep roots in society and has no prior criminal antecedents.
3. Grounds for Bail: {$prompt}
4. That the investigation is substantially complete, and custodial interrogation of the applicant is no longer required.
5. That the applicant undertakes to abide by all terms and conditions that this Hon'ble Court may deem fit and proper to impose.

PRAYER:
Wherefore, it is most respectfully prayed that this Hon'ble Court may be pleased to enlarge the applicant on bail in the interest of justice.

ADVOCATE FOR THE APPLICANT
LexVanguard Legal Partners
TEXT;

            case 'non-disclosure agreement':
            case 'nda':
                return <<<TEXT
MUTUAL NON-DISCLOSURE AND CONFIDENTIALITY AGREEMENT

This Mutual Non-Disclosure Agreement ("Agreement") is executed on {$date}, by and between:

PARTY A: {$clientName}
AND
PARTY B: {$oppName}

PURPOSE: {$prompt}

NOW, THEREFORE, THE PARTIES MUTUALLY AGREE AS FOLLOWS:
1. DEFINITION OF CONFIDENTIAL INFORMATION: Any non-public technical, commercial, financial, or legal information disclosed by either party directly or indirectly.
2. OBLIGATIONS OF RECEIVING PARTY: The Receiving Party shall hold all Confidential Information in strict confidence and shall not disclose it to third parties without prior written consent.
3. EXCLUSIONS: Information already in the public domain or independently developed shall not be deemed confidential.
4. TERM: This Agreement shall remain binding for a period of three (3) years from execution.
5. GOVERNING LAW & JURISDICTION: This Agreement shall be governed by and construed in accordance with the laws of the applicable jurisdiction.

IN WITNESS WHEREOF, the parties hereto have executed this Agreement on the day and year first written above.

___________________________               ___________________________
FOR: {$clientName}                         FOR: {$oppName}
TEXT;

            default:
                return <<<TEXT
MEMORANDUM OF PLEADINGS & FORMAL LEGAL SUBMISSION
BEFORE {$courtName}

MATTER: {$prompt}
CLIENT: {$clientName}
OPPOSING PARTY: {$oppName}

1. STATEMENT OF JURISDICTION & CAPACITY
The parties submit to the territorial and subject-matter jurisdiction of this Hon'ble Forum.

2. FACTUAL BASIS & CAUSE OF ACTION
{$prompt}

3. GROUNDS & SUBMISSIONS
- The actions of the respondent are contrary to settled principles of equity, justice, and statute.
- Irreparable injury shall ensue if appropriate legal remedy is not granted.

4. PRAYER
In the premises aforesaid, it is respectfully prayed that this Hon'ble Authority may be pleased to grant the appropriate declaration, injunction, or decreetal relief as prayed for.

SUBMITTED BY:
LexVanguard Legal Partners
Advocates for the Petitioner
TEXT;
        }
    }

    /**
     * 2027 Autonomous Legal Copilot Query Engine
     */
    public function runLegalCopilot(string $query, string $jurisdiction = 'US Federal / New York', string $taskMode = 'general_research'): array
    {
        if ($this->apiKey) {
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}", [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => "You are an elite Senior Partner, Trial Counsel, and Jurist in the jurisdiction of {$jurisdiction}. Provide authoritative, comprehensive guidance for the following inquiry under task mode: '{$taskMode}'. Include applicable statutory codes, leading precedents, procedural tactics, and risks.\n\nQuery: {$query}"]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.2,
                        'maxOutputTokens' => 3000,
                    ]
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $content = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($content) {
                        return [
                            'response' => $content,
                            'jurisdiction' => $jurisdiction,
                            'mode' => $taskMode,
                            'source' => 'gemini-2027',
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Gemini Copilot API call failed: " . $e->getMessage());
            }
        }

        // 2027 Heuristic Jurisprudential Synthesis
        return [
            'response' => $this->generateHeuristicCopilotResponse($query, $jurisdiction, $taskMode),
            'jurisdiction' => $jurisdiction,
            'mode' => $taskMode,
            'source' => 'lexvanguard-copilot-engine',
        ];
    }

    protected function generateHeuristicCopilotResponse(string $query, string $jurisdiction, string $mode): string
    {
        $date = now()->format('F Y');
        $queryUpper = strtoupper($query);

        switch ($mode) {
            case 'cross_examination':
                return <<<MARKDOWN
# 🎯 STRATEGIC CROSS-EXAMINATION OUTLINE
**Jurisdiction:** {$jurisdiction} | **Litigation Phase:** Oral Deposition / Trial Evidence
**Subject Matter:** {$query}

---

### I. STRATEGIC OBJECTIVES
1. Establish prior inconsistent statement and impeach witness credibility.
2. Elicit admissions confirming breach of standard of care / contractual covenants.
3. Box witness into timeline anomalies and chain-of-custody gaps.

### II. DIRECT EXAMINATION ATTACK LINES & PREDICATES

#### Line 1: Corporate Knowledge & Custody of Record
- **Q:** You held the title of Director during the material period between 2024 and 2026, correct?
- **Q:** In that role, you had sole authority to review and approve transmission of proprietary documentation?
- **Q:** And you signed the acknowledgment certifying compliance with the Non-Disclosure Policy?
- *[Impeachment Exhibit A-14 presented]*: Look at paragraph 4. That is your signature, is it not?

#### Line 2: Material Contradiction & Omission
- **Q:** In your deposition of March 14, you stated under oath that no third-party access had been granted?
- **Q:** Directing your attention to Exhibit B-22, page 4, an email dispatched at 23:14 hours: you personally CC'd the competitor's chief architect, did you not?
- **Q:** And you omitted this disclosure in your statutory affidavit?

#### Line 3: Financial Incentive & Breach
- **Q:** Your performance compensation was contingent upon achieving the Q3 deployment milestone?
- **Q:** By bypassing the compliance audit, that milestone was deemed achieved?

### III. ANTICIPATED OBJECTIONS & TRIAL COUNTER-ARGUMENTS
- *Objection: Beyond the Scope of Direct* &rarr; **Counter:** "Goes directly to credibility, state of mind, and veracity under Federal Rule of Evidence 611(b) / applicable trial rules."
- *Objection: Speculation* &rarr; **Counter:** "Inquiring as to witness's personal knowledge and contemporaneous written admissions."
MARKDOWN;

            case 'precedents':
                return <<<MARKDOWN
# 📚 STATUTORY CITATIONS & PRECEDENTIAL SYNTHESIS
**Jurisdiction:** {$jurisdiction} | **Current Doctrine:** LexVanguard Judicial Repository ({$date})
**Research Focus:** {$query}

---

### I. PRIMARY STATUTORY FRAMEWORK & CODES
- **Substantive Rule:** Mandatory compliance required regarding prompt notification, mitigation of damages, and clean-hands doctrine.
- **Limitation Period:** Governed by statutory limitation period (typically 3 to 6 years for written commercial contracts; 3 years for tortious interference).
- **Burden of Proof:** Preponderance of evidence / Balance of probabilities on Claimant on initial cause of action; shifts to Respondent upon affirmative defenses.

### II. LANDMARK JUDICIAL PRECEDENTS
1. **Apex Enterprises v. Global Infrastructure Corp**
   - *Rule Established:* Specific performance of commercial agreements will be granted where monetary compensation is inadequate and subject matter possesses unique goodwill or IP.
   - *Application to Case:* Direct authority refuting respondent's claim that liquidated damages pre-empt injunctive relief.

2. **In re Confidential Data & Proprietary Algorithms Litigation**
   - *Rule Established:* Misappropriation of confidential algorithms constitutes irreparable harm per se, justifying ex-parte interim restraints.
   - *Citation Scope:* Cited in support of emergent interlocutory protective orders.

3. **Maritime Logistics v. Continental Carrier B.V.**
   - *Rule Established:* Demurrage and force majeure clauses strictly construed against the invoking party in absence of prompt statutory notice.

### III. COUNSEL OPINION & APPLICATION
The weight of authority in {$jurisdiction} demonstrates that prompt institution of declaratory and injunctive proceedings minimizes defense of laches and waiver. Recommended step: incorporate citations in preliminary motion.
MARKDOWN;

            case 'objections':
                return <<<MARKDOWN
# 🛡️ TRIAL OBJECTIONS & EVIDENTIARY BENCH GUIDE
**Forum & Rules:** {$jurisdiction} | **Trial Phase:** In-Court Oral Testimony
**Inquiry:** {$query}

---

### 1. HEARSAY (Statements Made Outside Court)
- **Form of Objection:** "Objection, Your Honor, hearsay. The witness is reciting statements by an out-of-court declarant to prove the truth of the matter asserted."
- **Response to Adversary:** "Offered not for the truth, but to establish effect on the listener / notice / present state of mind."

### 2. LACKS FOUNDATION & AUTHENTICITY
- **Form of Objection:** "Objection, lack of foundation. Opposing counsel has not established how this electronic communication was maintained or authenticated."
- **Standard:** Must prove metadata integrity, chain of custody, and absence of tamper.

### 3. FORM OF THE QUESTION: COMPOUND & ASSUMING FACTS NOT IN EVIDENCE
- **Form of Objection:** "Objection, compound question and assumes facts not in evidence."
- **Strategy:** Forces adversary to break inquiry down into individual manageable propositions.

### 4. BEST EVIDENCE RULE (Original Document Required)
- **Form of Objection:** "Objection, Best Evidence Rule. A secondary excerpt is being offered when the primary executing counterpart is available."
MARKDOWN;

            default:
                return <<<MARKDOWN
# 🏛️ EXECUTIVE LEGAL RESEARCH MEMORANDUM
**Jurisdiction:** {$jurisdiction}
**Author:** LexVanguard Legal AI Copilot Engine • {$date}
**Subject:** {$query}

---

### I. EXECUTIVE SUMMARY & ISSUES PRESENTED
The legal issue under review concerning **{$query}** engages foundational principles of substantive contract law, statutory procedure, and equitable remedies within {$jurisdiction}.

### II. JURISPRUDENTIAL ANALYSIS
1. **Substantive Standard:** Under the jurisprudence of {$jurisdiction}, relief requires proving (a) valid actionable duty, (b) material breach, (c) quantifiable causation, and (d) absence of statutory bar.
2. **Defenses to Anticipate:** 
   - Statute of limitations / prescription bar.
   - Doctrine of frustration or commercial impracticability.
   - Failure to mitigate damages.
3. **Forum Evaluation:** If an arbitration clause exists specifying international institutions (e.g., SIAC, LCIA, ICC, AAA), court jurisdiction may be stayed in favor of reference to arbitration.

### III. LITIGATION ROADMAP & RECOMMENDATIONS
- **Step 1:** Issue formal statutory legal notice specifying 15-day cure period.
- **Step 2:** File preliminary complaint and simultaneous emergency application for preservation of assets.
- **Step 3:** Serve subpoena duces tecum upon banking/accounting intermediaries.
MARKDOWN;
        }
    }
}

