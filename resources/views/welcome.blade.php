<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LexVanguard Global Legal Partners • Digital Law Firm OS 2027</title>
    <meta name="description" content="LexVanguard 2027 AI-Integrated Global Law Firm Management System & Autonomous Judicial Practice Operating System">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Cinzel:wght@500;700;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        gold: {
                            300: '#fde68a',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        },
                        navy: {
                            900: '#090d16',
                            950: '#04070d',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Cinzel', 'serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .gold-gradient-text {
            background: linear-gradient(135deg, #fef08a 0%, #f59e0b 50%, #b45309 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glow {
            box-shadow: 0 0 50px -10px rgba(245, 158, 11, 0.25);
        }
    </style>
</head>
<body class="bg-navy-950 text-slate-100 min-h-screen font-sans selection:bg-amber-500 selection:text-black antialiased relative overflow-x-hidden">

    <!-- Ambient background light effects -->
    <div class="fixed top-0 left-1/4 w-[600px] h-[600px] bg-amber-500/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="fixed bottom-0 right-1/4 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[140px] pointer-events-none"></div>

    <!-- Navigation Header -->
    <nav class="sticky top-0 z-50 glass border-b border-white/5 py-4 px-6 md:px-12 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-black font-extrabold shadow-lg shadow-amber-500/20">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
            </div>
            <div>
                <span class="font-serif text-lg tracking-wider font-bold gold-gradient-text block leading-none">LEXVANGUARD</span>
                <span class="text-[10px] text-slate-400 tracking-widest font-mono uppercase">GLOBAL LEGAL OS • 2027</span>
            </div>
        </div>

        <div class="flex items-center space-x-4">
            <div class="hidden lg:flex items-center space-x-2 text-xs font-mono px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-slate-300">
                <span class="text-amber-400">Jurisdictions:</span>
                <span>🇺🇸 US</span>
                <span>🇬🇧 UK</span>
                <span>🇦🇪 UAE</span>
                <span>🇸🇬 SG</span>
                <span>🇮🇳 IN</span>
                <span>🇨🇦 CA</span>
                <span>🇦🇺 AU</span>
                <span>🇪🇺 EU</span>
            </div>
            <a href="/admin/cause-list-daily-board-page" class="hidden md:inline-flex items-center space-x-1.5 text-xs text-slate-300 hover:text-amber-400 transition-colors font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Daily Cause Board</span>
            </a>
            <a href="/admin/login" class="px-5 py-2 text-xs font-bold rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-black shadow-lg shadow-amber-500/25 transition-all transform hover:-translate-y-0.5">
                Launch Console &rarr;
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative px-6 md:px-12 pt-16 pb-16 max-w-7xl mx-auto text-center space-y-6">
        <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full glass border-amber-500/30 text-amber-400 text-xs font-mono font-medium">
            <span>⚖️ MULTI-JURISDICTIONAL COMMERCIAL LAW FIRM OS</span>
            <span>•</span>
            <span>2027 AI SUITE</span>
        </div>

        <h1 class="text-4xl md:text-6xl lg:text-7xl font-serif font-black tracking-tight max-w-5xl mx-auto leading-tight">
            Autonomous Practice Intelligence for <span class="gold-gradient-text">Global Law Firms</span>
        </h1>

        <p class="text-slate-400 text-base md:text-xl max-w-3xl mx-auto leading-relaxed">
            Unifying cross-border litigation, airport-style live cause boards, multi-currency IOLTA fiduciary escrows, and a 2027 AI Copilot for autonomous drafting, clause risk auditing, and cross-examination formulation.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
            <a href="/admin" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 hover:from-amber-500 hover:to-amber-700 text-black font-extrabold text-sm shadow-xl shadow-amber-500/25 transition-all transform hover:scale-105 flex items-center space-x-2">
                <span>Enter Management Console</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="/admin/ai-copilot-page" class="px-7 py-3.5 rounded-xl glass hover:bg-white/10 text-amber-300 font-semibold text-sm transition-all border-amber-500/30 flex items-center space-x-2">
                <span>⚡ Open 2027 AI Copilot</span>
            </a>
            <a href="/admin/cause-list-daily-board-page" class="px-7 py-3.5 rounded-xl glass hover:bg-white/10 text-slate-200 font-semibold text-sm transition-all border-white/10 flex items-center space-x-2">
                <span>📺 Live Cause List</span>
            </a>
        </div>

        <!-- 6 Dedicated Seeded Role Accounts -->
        <div class="glass max-w-4xl mx-auto p-6 rounded-3xl border-amber-500/20 text-left space-y-4 mt-10">
            <div class="flex items-center justify-between pb-3 border-b border-white/10">
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <span>🏛️ Pre-Seeded Dedicated Role Credentials</span>
                        <span class="px-2 py-0.5 text-[10px] font-mono bg-emerald-500/20 text-emerald-400 rounded-full border border-emerald-500/30">Active & Ready</span>
                    </h3>
                    <p class="text-xs text-slate-400">All accounts use the universal password: <span class="font-mono text-amber-400 font-bold">password</span></p>
                </div>
                <a href="/admin/login" class="text-xs text-amber-400 font-semibold hover:underline">
                    Go to Login &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <!-- Role 1: Super Admin -->
                <div class="p-3 rounded-2xl bg-black/40 border border-white/5 space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-amber-400">👑 Super Admin</span>
                        <span class="text-[10px] text-slate-500 font-mono">Managing Partner</span>
                    </div>
                    <div class="text-xs font-mono text-white font-semibold truncate">admin@lexvanguard.law</div>
                    <div class="text-[11px] text-slate-400">Eleanor Vance • Appellate Lead</div>
                </div>

                <!-- Role 2: Partner -->
                <div class="p-3 rounded-2xl bg-black/40 border border-white/5 space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-amber-300">⚖️ Senior Partner</span>
                        <span class="text-[10px] text-slate-500 font-mono">Arbitration Head</span>
                    </div>
                    <div class="text-xs font-mono text-white font-semibold truncate">partner@lexvanguard.law</div>
                    <div class="text-[11px] text-slate-400">Marcus Stone, KC • SIAC/LCIA</div>
                </div>

                <!-- Role 3: Lawyer -->
                <div class="p-3 rounded-2xl bg-black/40 border border-white/5 space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-sky-400">🏛️ Senior Trial Lawyer</span>
                        <span class="text-[10px] text-slate-500 font-mono">Associate Counsel</span>
                    </div>
                    <div class="text-xs font-mono text-white font-semibold truncate">lawyer@lexvanguard.law</div>
                    <div class="text-[11px] text-slate-400">Sarah Lin, Esq. • IP Litigation</div>
                </div>

                <!-- Role 4: Paralegal -->
                <div class="p-3 rounded-2xl bg-black/40 border border-white/5 space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-emerald-400">📁 Lead Paralegal</span>
                        <span class="text-[10px] text-slate-500 font-mono">Litigation Support</span>
                    </div>
                    <div class="text-xs font-mono text-white font-semibold truncate">paralegal@lexvanguard.law</div>
                    <div class="text-[11px] text-slate-400">Julian Hayes • Discovery Lead</div>
                </div>

                <!-- Role 5: Accountant -->
                <div class="p-3 rounded-2xl bg-black/40 border border-white/5 space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-purple-400">💳 Trust Accountant</span>
                        <span class="text-[10px] text-slate-500 font-mono">IOLTA Comptroller</span>
                    </div>
                    <div class="text-xs font-mono text-white font-semibold truncate">accountant@lexvanguard.law</div>
                    <div class="text-[11px] text-slate-400">Elena Rostova, CPA • Billing Head</div>
                </div>

                <!-- Role 6: Client -->
                <div class="p-3 rounded-2xl bg-black/40 border border-white/5 space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-pink-400">🏢 Corporate Client</span>
                        <span class="text-[10px] text-slate-500 font-mono">In-House CLO</span>
                    </div>
                    <div class="text-xs font-mono text-white font-semibold truncate">client@lexvanguard.law</div>
                    <div class="text-[11px] text-slate-400">David Sterling • NovaTech Global</div>
                </div>
            </div>
        </div>
    </header>

    <!-- Global Jurisdictions Banner -->
    <section class="max-w-7xl mx-auto px-6 md:px-12 py-6">
        <div class="glass p-5 rounded-2xl border-white/5 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <span class="text-2xl">🌐</span>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">Full International Country & Currency Architecture</h4>
                    <p class="text-[11px] text-slate-400">Configured for multi-currency retainers, statutory fee tables, and bilateral court procedures</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 text-xs font-mono">
                <span class="px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-slate-300">🇺🇸 United States (USD $)</span>
                <span class="px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-slate-300">🇬🇧 United Kingdom (GBP £)</span>
                <span class="px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-slate-300">🇦🇪 UAE DIFC (AED د.إ)</span>
                <span class="px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-slate-300">🇸🇬 Singapore SICC (SGD S$)</span>
                <span class="px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-slate-300">🇮🇳 India (INR ₹)</span>
                <span class="px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-slate-300">🇨🇦 Canada (CAD CA$)</span>
                <span class="px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-slate-300">🇦🇺 Australia (AUD A$)</span>
                <span class="px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-slate-300">🇪🇺 European Union (EUR €)</span>
            </div>
        </div>
    </section>

    <!-- Functional Modules Grid -->
    <section class="max-w-7xl mx-auto px-6 md:px-12 py-12 space-y-10">
        <div class="text-center space-y-2">
            <h2 class="text-2xl md:text-3xl font-serif font-bold text-white">2027 Practice Architecture</h2>
            <p class="text-xs text-slate-400 font-mono uppercase tracking-widest">Built with Laravel 12 & Filament v5</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card 1: 2027 AI Legal Copilot -->
            <div class="glass p-6 rounded-2xl border-amber-500/30 hover:border-amber-400 transition-all space-y-3 group">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center font-bold text-xl group-hover:scale-110 transition-transform">
                    ⚡
                </div>
                <h3 class="text-lg font-bold text-white flex items-center justify-between">
                    <span>2027 AI Legal Copilot</span>
                    <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30">NEW</span>
                </h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Interactive multi-jurisdiction research, cross-examination question sequences, trial objection bench guides, and precedent analysis powered by Gemini 1.5.
                </p>
                <div class="pt-2 text-[11px] font-mono text-amber-400/90">&rarr; AiCopilotPage & LegalAiService</div>
            </div>

            <!-- Card 2: Litigation & Cases -->
            <div class="glass p-6 rounded-2xl border-white/5 hover:border-amber-500/40 transition-all space-y-3 group">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center font-bold text-xl group-hover:scale-110 transition-transform">
                    ⚖️
                </div>
                <h3 class="text-lg font-bold text-white">Litigation & Trial Lifecycle</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    CNR number indexation, hearing dates history, multi-counsel assignment, pleadings, prayer relief tracking, companion matters, and disposal decrees.
                </p>
                <div class="pt-2 text-[11px] font-mono text-blue-400/90">&rarr; LegalCase & HearingDate Resources</div>
            </div>

            <!-- Card 3: Cause List Board -->
            <div class="glass p-6 rounded-2xl border-white/5 hover:border-amber-500/40 transition-all space-y-3 group">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center font-bold text-xl group-hover:scale-110 transition-transform">
                    📺
                </div>
                <h3 class="text-lg font-bold text-white">Live Cause List Board</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Airport/Tribunal style real-time display board for today’s hearings, courtroom allocation, presiding judges, and advocate attendance status.
                </p>
                <div class="pt-2 text-[11px] font-mono text-emerald-400/90">&rarr; CauseListDailyBoardPage</div>
            </div>

            <!-- Card 4: Fiduciary Trust Accounting -->
            <div class="glass p-6 rounded-2xl border-white/5 hover:border-amber-500/40 transition-all space-y-3 group">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center font-bold text-xl group-hover:scale-110 transition-transform">
                    💳
                </div>
                <h3 class="text-lg font-bold text-white">IOLTA Trust & Multi-Currency</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Fiduciary client trust accounting, automated PDF invoice generation in USD, GBP, AED, EUR, billable timekeeping, and ledger audit trails.
                </p>
                <div class="pt-2 text-[11px] font-mono text-purple-400/90">&rarr; Invoices, BankAccounts & Ledger</div>
            </div>

            <!-- Card 5: Autonomous Legal Drafter -->
            <div class="glass p-6 rounded-2xl border-white/5 hover:border-amber-500/40 transition-all space-y-3 group">
                <div class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-400 flex items-center justify-center font-bold text-xl group-hover:scale-110 transition-transform">
                    📄
                </div>
                <h3 class="text-lg font-bold text-white">AI Document & Contract Drafter</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Statutory drafting for Legal Notices, Bail Petitions, NDAs, Injunctions, with clause extraction and automated contract liability risk auditing.
                </p>
                <div class="pt-2 text-[11px] font-mono text-rose-400/90">&rarr; AiDocumentDrafter & RiskAnalyzer</div>
            </div>

            <!-- Card 6: Ethical Conflict Engine -->
            <div class="glass p-6 rounded-2xl border-white/5 hover:border-amber-500/40 transition-all space-y-3 group">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center font-bold text-xl group-hover:scale-110 transition-transform">
                    🛡️
                </div>
                <h3 class="text-lg font-bold text-white">Conflict of Interest Scanner</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Real-time entity screening across active litigants, past corporate clients, adverse counsels, and companion disputes with Bar rule adherence.
                </p>
                <div class="pt-2 text-[11px] font-mono text-amber-400/90">&rarr; ConflictCheck Resource & Audit</div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-white/5 py-12 px-6 md:px-12 text-center text-xs text-slate-500 max-w-7xl mx-auto space-y-3">
        <div class="font-serif text-sm font-bold text-slate-300">LEXVANGUARD GLOBAL LEGAL PARTNERS LLP</div>
        <p>New York • London • Dubai DIFC • Singapore SICC • New Delhi</p>
        <p class="text-[11px] text-slate-600">Containerized Docker Architecture • MariaDB 11 • Redis • Laravel 12 • Filament v5</p>
    </footer>

</body>
</html>
