# ************************************************************
# Sequel Ace SQL dump
# Version 20095
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: localhost (MySQL 9.2.0)
# Database: invoicio
# Generation Time: 2026-03-04 13:43:10 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table activity_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `activity_log`;

CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table ai_insights
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ai_insights`;

CREATE TABLE `ai_insights` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `prompt` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `response` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `response_html` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `generated_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ai_insights_user_id_foreign` (`user_id`),
  KEY `ai_insights_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  CONSTRAINT `ai_insights_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `ai_insights` WRITE;
/*!40000 ALTER TABLE `ai_insights` DISABLE KEYS */;

INSERT INTO `ai_insights` (`id`, `user_id`, `type`, `subject_type`, `subject_id`, `prompt`, `response`, `response_html`, `generated_at`, `created_at`, `updated_at`)
VALUES
	(1,1,'invoice_insights','App\\Models\\Invoice',4,'Please analyze this invoice and give me insights:\n\n\n    INVOICE DETAILS:\n    - Invoice Number: #INV-00000004\n    - Status: sent\n    - Issue Date: Mar 04, 2026\n    - Due Date: Apr 03, 2026\n    - Due Situation: Due in 29.444288908275 days\n\n    CLIENT INFO:\n    - Name: San Miguel Corp\n    - Company: \n    - Email: \n\n    FINANCIAL BREAKDOWN:\n    - Subtotal: $60.00\n    - Tax (0.00%): $0.00\n    - Total: $60.00\n    - Total Paid: $0\n    - Balance Due: $60\n\n    LINE ITEMS:\n    - Motolite Gold: 1.00 x $60.00 = $60.00\n\n    PAYMENT HISTORY:\n    No payments recorded yet.\n\n    NOTES: \n    TODAY\'S DATE: Mar 04, 2026\n        ','# 📊 Invoice Analysis: #INV-00000004\n\n## 🎯 **Quick Overview**\nThis is a simple $60 invoice for a Motolite Gold battery sent to San Miguel Corp. The invoice was just issued today and payment is due in about 30 days.\n\n## ⚠️ **Key Alerts**\n- **Missing Contact Info**: No email address on file for San Miguel Corp - this could delay payment communications\n- **No Tax Applied**: 0% tax rate might need verification depending on your location and tax requirements\n\n## 💰 **Financial Summary**\n- **Amount Due**: $60.00\n- **Payment Status**: Unpaid (just sent today)\n- **Due Date**: April 3, 2026 (30-day terms)\n\n## 📈 **Recommendations**\n\n### Immediate Actions:\n1. **Update client email** - Add San Miguel Corp\'s email for better communication\n2. **Verify tax rate** - Confirm 0% tax is correct for this client/product\n\n### Follow-up Strategy:\n- **Week 3**: Send friendly payment reminder\n- **Due date**: Follow up if payment not received\n- **Week after due**: Send overdue notice\n\n## ✅ **Positive Notes**\n- Clean, simple invoice with clear item description\n- Reasonable 30-day payment terms\n- Professional invoice numbering system in place\n\n*Everything looks standard for a new invoice! Just focus on getting that client email updated for smoother communication.* 📧','<h1>📊 Invoice Analysis: #INV-00000004</h1>\n<h2>🎯 <strong>Quick Overview</strong></h2>\n<p>This is a simple $60 invoice for a Motolite Gold battery sent to San Miguel Corp. The invoice was just issued today and payment is due in about 30 days.</p>\n<h2>⚠️ <strong>Key Alerts</strong></h2>\n<ul>\n<li><strong>Missing Contact Info</strong>: No email address on file for San Miguel Corp - this could delay payment communications</li>\n<li><strong>No Tax Applied</strong>: 0% tax rate might need verification depending on your location and tax requirements</li>\n</ul>\n<h2>💰 <strong>Financial Summary</strong></h2>\n<ul>\n<li><strong>Amount Due</strong>: $60.00</li>\n<li><strong>Payment Status</strong>: Unpaid (just sent today)</li>\n<li><strong>Due Date</strong>: April 3, 2026 (30-day terms)</li>\n</ul>\n<h2>📈 <strong>Recommendations</strong></h2>\n<h3>Immediate Actions:</h3>\n<ol>\n<li><strong>Update client email</strong> - Add San Miguel Corp\'s email for better communication</li>\n<li><strong>Verify tax rate</strong> - Confirm 0% tax is correct for this client/product</li>\n</ol>\n<h3>Follow-up Strategy:</h3>\n<ul>\n<li><strong>Week 3</strong>: Send friendly payment reminder</li>\n<li><strong>Due date</strong>: Follow up if payment not received</li>\n<li><strong>Week after due</strong>: Send overdue notice</li>\n</ul>\n<h2>✅ <strong>Positive Notes</strong></h2>\n<ul>\n<li>Clean, simple invoice with clear item description</li>\n<li>Reasonable 30-day payment terms</li>\n<li>Professional invoice numbering system in place</li>\n</ul>\n<p><em>Everything looks standard for a new invoice! Just focus on getting that client email updated for smoother communication.</em> 📧</p>','2026-03-04 13:20:23','2026-03-04 13:20:23','2026-03-04 13:20:23'),
	(2,1,'client_insights','App\\Models\\Client',1,'Please analyze this client\'s payment behavior:\n\n\n    CLIENT PROFILE:\n    - Name: San Miguel Corp\n    - Company: \n    - Email: \n    - Phone: \n    - Address: \n\n    INVOICE STATISTICS:\n    - Total Invoices: 3\n    - Total Value: $164\n    - Total Collected: $40\n    - Total Outstanding: $124\n    - Overdue Invoices: 0\n    - Average Days to Pay: 0 days\n\n    INVOICE BREAKDOWN:\n    - #INV-00000004 | sent | $60.00 | Paid: $0 | Balance: $60 | Due: Apr 03, 2026\n- #INV-00000003 | sent | $39.00 | Paid: $0 | Balance: $39 | Due: Apr 03, 2026\n- #INV-00000001 | paid | $65.00 | Paid: $40 | Balance: $25 | Due: Apr 03, 2026\n\n    TODAY\'S DATE: Mar 04, 2026\n        ','# 📊 Payment Analysis: San Miguel Corp\n\n## 🚨 **Key Findings**\n\n**Payment Collection Rate:** 24% ($40 collected out of $164 total) ⚠️  \n**Outstanding Balance:** $124  \n**Risk Level:** 🟡 **MODERATE** (due to incomplete payments)\n\n---\n\n## 💡 **Critical Insights**\n\n### 🔍 **What\'s Happening:**\n- **Partial Payment Pattern:** San Miguel paid only $40 out of $65 on invoice #INV-00000001, leaving $25 unpaid\n- **Two Pending Invoices:** $60 + $39 = $99 in completely unpaid invoices\n- **Good News:** All invoices are current (due April 3rd, still 30 days away) ✅\n\n### ⚡ **Red Flags:**\n1. **Incomplete Payment:** They didn\'t pay invoice #INV-00000001 in full despite marking it as \"paid\"\n2. **Poor Collection Rate:** Only 24% collected suggests potential cash flow issues\n\n---\n\n## 🎯 **Immediate Action Items**\n\n### 📞 **This Week:**\n1. **Follow up on partial payment** - Ask why only $40 was paid on the $65 invoice\n2. **Clarify payment terms** - Ensure they understand full payment is expected\n3. **Request payment plan** if they\'re experiencing cash flow issues\n\n### 📋 **Before April 3rd:**\n1. **Send payment reminders** at 2 weeks and 1 week before due date\n2. **Consider offering early payment discount** (2-3%) to encourage faster payment\n3. **Request payment method on file** (auto-pay setup)\n\n### 🛡️ **Risk Management:**\n- **Require 50% deposit** on future invoices over $50\n- **Shorten payment terms** to Net 15 instead of Net 30\n- **Consider credit check** if this is a new client relationship\n\n---\n\n## 📈 **Recommendation**\n\nSan Miguel Corp shows mixed signals - they\'re not technically overdue, but the partial payment is concerning. **Proactive communication is key!** Address the payment discrepancy now before it becomes a bigger issue.\n\n**Priority:** 🔥 **HIGH** - Contact within 48 hours about the partial payment.','<h1>📊 Payment Analysis: San Miguel Corp</h1>\n<h2>🚨 <strong>Key Findings</strong></h2>\n<p><strong>Payment Collection Rate:</strong> 24% ($40 collected out of $164 total) ⚠️<br />\n<strong>Outstanding Balance:</strong> $124<br />\n<strong>Risk Level:</strong> 🟡 <strong>MODERATE</strong> (due to incomplete payments)</p>\n<hr />\n<h2>💡 <strong>Critical Insights</strong></h2>\n<h3>🔍 <strong>What\'s Happening:</strong></h3>\n<ul>\n<li><strong>Partial Payment Pattern:</strong> San Miguel paid only $40 out of $65 on invoice #INV-00000001, leaving $25 unpaid</li>\n<li><strong>Two Pending Invoices:</strong> $60 + $39 = $99 in completely unpaid invoices</li>\n<li><strong>Good News:</strong> All invoices are current (due April 3rd, still 30 days away) ✅</li>\n</ul>\n<h3>⚡ <strong>Red Flags:</strong></h3>\n<ol>\n<li><strong>Incomplete Payment:</strong> They didn\'t pay invoice #INV-00000001 in full despite marking it as \"paid\"</li>\n<li><strong>Poor Collection Rate:</strong> Only 24% collected suggests potential cash flow issues</li>\n</ol>\n<hr />\n<h2>🎯 <strong>Immediate Action Items</strong></h2>\n<h3>📞 <strong>This Week:</strong></h3>\n<ol>\n<li><strong>Follow up on partial payment</strong> - Ask why only $40 was paid on the $65 invoice</li>\n<li><strong>Clarify payment terms</strong> - Ensure they understand full payment is expected</li>\n<li><strong>Request payment plan</strong> if they\'re experiencing cash flow issues</li>\n</ol>\n<h3>📋 <strong>Before April 3rd:</strong></h3>\n<ol>\n<li><strong>Send payment reminders</strong> at 2 weeks and 1 week before due date</li>\n<li><strong>Consider offering early payment discount</strong> (2-3%) to encourage faster payment</li>\n<li><strong>Request payment method on file</strong> (auto-pay setup)</li>\n</ol>\n<h3>🛡️ <strong>Risk Management:</strong></h3>\n<ul>\n<li><strong>Require 50% deposit</strong> on future invoices over $50</li>\n<li><strong>Shorten payment terms</strong> to Net 15 instead of Net 30</li>\n<li><strong>Consider credit check</strong> if this is a new client relationship</li>\n</ul>\n<hr />\n<h2>📈 <strong>Recommendation</strong></h2>\n<p>San Miguel Corp shows mixed signals - they\'re not technically overdue, but the partial payment is concerning. <strong>Proactive communication is key!</strong> Address the payment discrepancy now before it becomes a bigger issue.</p>\n<p><strong>Priority:</strong> 🔥 <strong>HIGH</strong> - Contact within 48 hours about the partial payment.</p>','2026-03-04 13:20:59','2026-03-04 13:20:59','2026-03-04 13:20:59'),
	(3,1,'financial_report_30','App\\Models\\User',1,'Generate a comprehensive financial report for this period:\n\n\nREPORT PERIOD: Last 30 Days (Feb 02, 2026 to Mar 04, 2026)\n\nINVOICE SUMMARY:\n- Total Invoices Created: 4\n- Total Invoiced Amount: $203\n- Paid: $65 (1 invoices)\n- Pending/Sent: $138 (3 invoices)\n- Overdue: $0 (0 invoices)\n- Draft: $0\n- Collection Rate: 32%\n\nPAYMENTS COLLECTED:\n- Total Collected: $40\n- Number of Payments: 1\n\nPAYMENT METHODS:\n- cash: $40 (1 payments)\n\nCLIENT OVERVIEW:\n- Total Clients: 3\n- Active Clients This Period: 2\n\nTOP CLIENTS BY REVENUE:\n- San Miguel Corp: $164 (3 invoices)\n- Shell Philippines: $39 (1 invoices)\n\nOVERDUE INVOICES:\nNone - Great job!\n\nALL TIME STATS:\n- Total Revenue (All Time): $65\n- Total Invoices (All Time): 4\n        ','# 📊 FINANCIAL REPORT - INVOICIO\n**Period: February 2, 2026 - March 4, 2026**\n\n---\n\n## 🎯 EXECUTIVE SUMMARY\n\nYour business shows **strong invoice creation activity** with 4 new invoices totaling $203, though collection performance requires immediate attention. While maintaining excellent payment discipline with zero overdue accounts, the current collection rate of 32% indicates significant cash flow optimization opportunities.\n\n---\n\n## 💰 KEY FINANCIAL METRICS\n\n### Revenue Performance\n| Metric | Amount | Status |\n|--------|--------|--------|\n| **Total Invoiced** | $203 | 📈 Strong |\n| **Collections** | $40 | ⚠️ Low |\n| **Outstanding Receivables** | $138 | 🔍 Monitor |\n| **Collection Rate** | 32% | 🚨 Critical |\n\n### Payment Status Breakdown\n- ✅ **Paid**: $65 (32% of total invoiced)\n- ⏳ **Pending**: $138 (68% of total invoiced)\n- ✨ **Overdue**: $0 (Excellent!)\n\n---\n\n## 📈 BUSINESS ACTIVITY ANALYSIS\n\n### 🏢 Client Engagement\n- **Total Clients**: 3\n- **Active Clients**: 2 (67% engagement rate)\n- **New Invoice Activity**: 4 invoices created\n\n### 🏆 Top Performing Clients\n1. **San Miguel Corp** 🥇\n   - Revenue: $164 (81% of total invoiced)\n   - Invoices: 3\n   - Status: Key account driving majority of business\n\n2. **Shell Philippines** 🥈\n   - Revenue: $39 (19% of total invoiced)\n   - Invoices: 1\n   - Status: Secondary but consistent client\n\n---\n\n## 💳 PAYMENT INSIGHTS\n\n### Collection Summary\n- **Total Collected**: $40\n- **Payment Method**: 100% Cash payments\n- **Payment Frequency**: 1 transaction\n\n### 🚨 CRITICAL OBSERVATION\nThere\'s a **significant discrepancy** between invoiced amount ($203) and payments received ($40), with total paid invoices showing $65. This suggests potential data reconciliation issues that require immediate investigation.\n\n---\n\n## 🔍 TREND ANALYSIS\n\n### Positive Indicators ✅\n- **Zero overdue invoices** - Excellent payment discipline\n- **Strong client concentration** with San Miguel Corp\n- **Active invoice generation** showing business momentum\n\n### Areas of Concern ⚠️\n- **Low collection rate** (32%) well below industry standard of 85-95%\n- **High outstanding receivables** ($138) creating cash flow risk\n- **Limited payment method diversity** (cash only)\n\n---\n\n## 🎯 ACTIONABLE RECOMMENDATIONS\n\n### 🚨 Immediate Actions (Next 7 Days)\n1. **Follow up on pending invoices** - $138 outstanding\n2. **Reconcile payment data** - Address collection vs. paid invoice discrepancy\n3. **Contact San Miguel Corp** regarding their 3 pending invoices\n\n### 📈 Strategic Improvements (Next 30 Days)\n1. **Implement payment terms** - Consider offering early payment discounts\n2. **Diversify payment methods** - Add digital payment options\n3. **Establish collection procedures** - Systematic follow-up schedule\n4. **Client expansion** - Activate the 3rd inactive client\n\n### 🏗️ Long-term Growth (Next 90 Days)\n1. **Target collection rate improvement** to 80%+\n2. **Develop recurring revenue streams**\n3. **Expand client base** beyond current 3 clients\n4. **Implement automated payment reminders**\n\n---\n\n## 📊 BENCHMARKING & TARGETS\n\n| Metric | Current | Industry Standard | Target |\n|--------|---------|-------------------|---------|\n| Collection Rate | 32% | 85-95% | 75% |\n| Days Sales Outstanding | High | 30-45 days | 30 days |\n| Client Diversity | Low | Varied | 5+ active clients |\n\n---\n\n## 💡 CONCLUSION\n\nWhile your business demonstrates strong operational discipline with zero overdue accounts, **cash flow optimization is critical**. Focus on improving collection rates and diversifying both clients and payment methods to ensure sustainable growth.\n\n**Next Review**: Recommended in 2 weeks to monitor collection improvements\n\n---\n\n*Report generated by Invoicio Analytics | Contact your account manager for detailed collection strategies*','<h1>📊 FINANCIAL REPORT - INVOICIO</h1>\n<p><strong>Period: February 2, 2026 - March 4, 2026</strong></p>\n<hr />\n<h2>🎯 EXECUTIVE SUMMARY</h2>\n<p>Your business shows <strong>strong invoice creation activity</strong> with 4 new invoices totaling $203, though collection performance requires immediate attention. While maintaining excellent payment discipline with zero overdue accounts, the current collection rate of 32% indicates significant cash flow optimization opportunities.</p>\n<hr />\n<h2>💰 KEY FINANCIAL METRICS</h2>\n<h3>Revenue Performance</h3>\n<table>\n<thead>\n<tr>\n<th>Metric</th>\n<th>Amount</th>\n<th>Status</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td><strong>Total Invoiced</strong></td>\n<td>$203</td>\n<td>📈 Strong</td>\n</tr>\n<tr>\n<td><strong>Collections</strong></td>\n<td>$40</td>\n<td>⚠️ Low</td>\n</tr>\n<tr>\n<td><strong>Outstanding Receivables</strong></td>\n<td>$138</td>\n<td>🔍 Monitor</td>\n</tr>\n<tr>\n<td><strong>Collection Rate</strong></td>\n<td>32%</td>\n<td>🚨 Critical</td>\n</tr>\n</tbody>\n</table>\n<h3>Payment Status Breakdown</h3>\n<ul>\n<li>✅ <strong>Paid</strong>: $65 (32% of total invoiced)</li>\n<li>⏳ <strong>Pending</strong>: $138 (68% of total invoiced)</li>\n<li>✨ <strong>Overdue</strong>: $0 (Excellent!)</li>\n</ul>\n<hr />\n<h2>📈 BUSINESS ACTIVITY ANALYSIS</h2>\n<h3>🏢 Client Engagement</h3>\n<ul>\n<li><strong>Total Clients</strong>: 3</li>\n<li><strong>Active Clients</strong>: 2 (67% engagement rate)</li>\n<li><strong>New Invoice Activity</strong>: 4 invoices created</li>\n</ul>\n<h3>🏆 Top Performing Clients</h3>\n<ol>\n<li>\n<p><strong>San Miguel Corp</strong> 🥇</p>\n<ul>\n<li>Revenue: $164 (81% of total invoiced)</li>\n<li>Invoices: 3</li>\n<li>Status: Key account driving majority of business</li>\n</ul>\n</li>\n<li>\n<p><strong>Shell Philippines</strong> 🥈</p>\n<ul>\n<li>Revenue: $39 (19% of total invoiced)</li>\n<li>Invoices: 1</li>\n<li>Status: Secondary but consistent client</li>\n</ul>\n</li>\n</ol>\n<hr />\n<h2>💳 PAYMENT INSIGHTS</h2>\n<h3>Collection Summary</h3>\n<ul>\n<li><strong>Total Collected</strong>: $40</li>\n<li><strong>Payment Method</strong>: 100% Cash payments</li>\n<li><strong>Payment Frequency</strong>: 1 transaction</li>\n</ul>\n<h3>🚨 CRITICAL OBSERVATION</h3>\n<p>There\'s a <strong>significant discrepancy</strong> between invoiced amount ($203) and payments received ($40), with total paid invoices showing $65. This suggests potential data reconciliation issues that require immediate investigation.</p>\n<hr />\n<h2>🔍 TREND ANALYSIS</h2>\n<h3>Positive Indicators ✅</h3>\n<ul>\n<li><strong>Zero overdue invoices</strong> - Excellent payment discipline</li>\n<li><strong>Strong client concentration</strong> with San Miguel Corp</li>\n<li><strong>Active invoice generation</strong> showing business momentum</li>\n</ul>\n<h3>Areas of Concern ⚠️</h3>\n<ul>\n<li><strong>Low collection rate</strong> (32%) well below industry standard of 85-95%</li>\n<li><strong>High outstanding receivables</strong> ($138) creating cash flow risk</li>\n<li><strong>Limited payment method diversity</strong> (cash only)</li>\n</ul>\n<hr />\n<h2>🎯 ACTIONABLE RECOMMENDATIONS</h2>\n<h3>🚨 Immediate Actions (Next 7 Days)</h3>\n<ol>\n<li><strong>Follow up on pending invoices</strong> - $138 outstanding</li>\n<li><strong>Reconcile payment data</strong> - Address collection vs. paid invoice discrepancy</li>\n<li><strong>Contact San Miguel Corp</strong> regarding their 3 pending invoices</li>\n</ol>\n<h3>📈 Strategic Improvements (Next 30 Days)</h3>\n<ol>\n<li><strong>Implement payment terms</strong> - Consider offering early payment discounts</li>\n<li><strong>Diversify payment methods</strong> - Add digital payment options</li>\n<li><strong>Establish collection procedures</strong> - Systematic follow-up schedule</li>\n<li><strong>Client expansion</strong> - Activate the 3rd inactive client</li>\n</ol>\n<h3>🏗️ Long-term Growth (Next 90 Days)</h3>\n<ol>\n<li><strong>Target collection rate improvement</strong> to 80%+</li>\n<li><strong>Develop recurring revenue streams</strong></li>\n<li><strong>Expand client base</strong> beyond current 3 clients</li>\n<li><strong>Implement automated payment reminders</strong></li>\n</ol>\n<hr />\n<h2>📊 BENCHMARKING &amp; TARGETS</h2>\n<table>\n<thead>\n<tr>\n<th>Metric</th>\n<th>Current</th>\n<th>Industry Standard</th>\n<th>Target</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td>Collection Rate</td>\n<td>32%</td>\n<td>85-95%</td>\n<td>75%</td>\n</tr>\n<tr>\n<td>Days Sales Outstanding</td>\n<td>High</td>\n<td>30-45 days</td>\n<td>30 days</td>\n</tr>\n<tr>\n<td>Client Diversity</td>\n<td>Low</td>\n<td>Varied</td>\n<td>5+ active clients</td>\n</tr>\n</tbody>\n</table>\n<hr />\n<h2>💡 CONCLUSION</h2>\n<p>While your business demonstrates strong operational discipline with zero overdue accounts, <strong>cash flow optimization is critical</strong>. Focus on improving collection rates and diversifying both clients and payment methods to ensure sustainable growth.</p>\n<p><strong>Next Review</strong>: Recommended in 2 weeks to monitor collection improvements</p>\n<hr />\n<p><em>Report generated by Invoicio Analytics | Contact your account manager for detailed collection strategies</em></p>','2026-03-04 13:29:59','2026-03-04 13:29:59','2026-03-04 13:29:59'),
	(4,1,'client_insights','App\\Models\\Client',3,'Please analyze this client\'s payment behavior:\n\n\n    CLIENT PROFILE:\n    - Name: Puregold\n    - Company: \n    - Email: \n    - Phone: \n    - Address: \n\n    INVOICE STATISTICS:\n    - Total Invoices: 2\n    - Total Value: $230\n    - Total Collected: $0\n    - Total Outstanding: $230\n    - Overdue Invoices: 0\n    - Average Days to Pay: N/A days\n\n    INVOICE BREAKDOWN:\n    - #INV-00000006 | sent | $165.00 | Paid: $0 | Balance: $165 | Due: Apr 15, 2026\n- #INV-00000005 | sent | $65.00 | Paid: $0 | Balance: $65 | Due: Mar 30, 2026\n\n    TODAY\'S DATE: Mar 04, 2026\n        ','# 📊 Client Payment Analysis: Puregold\n\n## 🚨 **Current Status: NEW CLIENT - EARLY MONITORING**\n\n### **Key Metrics at a Glance**\n- 💰 **Total Outstanding**: $230\n- 📋 **Active Invoices**: 2\n- ⏰ **Payment History**: None yet\n- 🎯 **Risk Level**: **LOW** (both invoices not yet due)\n\n---\n\n## 📈 **Payment Behavior Assessment**\n\n### **Current Situation** ✅\n- Both invoices are **not overdue** - this is good news!\n- Invoice #INV-00000005 ($65) due March 30, 2026 (26 days from now)\n- Invoice #INV-00000006 ($165) due April 15, 2026 (42 days from now)\n\n### **What This Means** 🤔\nSince this appears to be a **new client relationship**, there\'s no payment history to analyze yet. However, we can establish a baseline for monitoring.\n\n---\n\n## 🎯 **Recommended Actions**\n\n### **Immediate Steps** (Next 7 days)\n1. **📧 Send a friendly welcome email** confirming receipt of both invoices\n2. **📞 Make contact** to establish relationship and confirm payment processes\n3. **📝 Document preferred payment methods** and any special terms\n\n### **Monitoring Schedule** 📅\n- **March 23, 2026**: Send friendly reminder for invoice #INV-00000005 (1 week before due)\n- **March 30, 2026**: Follow up on due date if payment not received\n- **April 8, 2026**: Send reminder for invoice #INV-00000006 (1 week before due)\n\n---\n\n## 🎖️ **Client Relationship Strategy**\n\n### **Build Strong Foundation** 🏗️\n- Establish clear communication channels\n- Confirm their preferred invoice format\n- Set expectations for future payment terms\n- Consider offering early payment discounts (2% if paid within 10 days)\n\n### **Red Flags to Watch** 🚩\n- Late payment on first invoice (March 30th)\n- Lack of response to communications\n- Requests for extended payment terms without discussion\n\n---\n\n## 💡 **Pro Tips for Success**\n\n1. **First impressions matter** - Be proactive but not pushy\n2. **Document everything** - Start building their payment profile now\n3. **Stay positive** - They\'re not overdue, so maintain good relationship\n4. **Be prepared** - Have a collection process ready if needed\n\n**Next Review Date**: April 1, 2026 📅\n\n*Keep monitoring this client closely as they establish their payment pattern with your business!* 🎯','<h1>📊 Client Payment Analysis: Puregold</h1>\n<h2>🚨 <strong>Current Status: NEW CLIENT - EARLY MONITORING</strong></h2>\n<h3><strong>Key Metrics at a Glance</strong></h3>\n<ul>\n<li>💰 <strong>Total Outstanding</strong>: $230</li>\n<li>📋 <strong>Active Invoices</strong>: 2</li>\n<li>⏰ <strong>Payment History</strong>: None yet</li>\n<li>🎯 <strong>Risk Level</strong>: <strong>LOW</strong> (both invoices not yet due)</li>\n</ul>\n<hr />\n<h2>📈 <strong>Payment Behavior Assessment</strong></h2>\n<h3><strong>Current Situation</strong> ✅</h3>\n<ul>\n<li>Both invoices are <strong>not overdue</strong> - this is good news!</li>\n<li>Invoice #INV-00000005 ($65) due March 30, 2026 (26 days from now)</li>\n<li>Invoice #INV-00000006 ($165) due April 15, 2026 (42 days from now)</li>\n</ul>\n<h3><strong>What This Means</strong> 🤔</h3>\n<p>Since this appears to be a <strong>new client relationship</strong>, there\'s no payment history to analyze yet. However, we can establish a baseline for monitoring.</p>\n<hr />\n<h2>🎯 <strong>Recommended Actions</strong></h2>\n<h3><strong>Immediate Steps</strong> (Next 7 days)</h3>\n<ol>\n<li><strong>📧 Send a friendly welcome email</strong> confirming receipt of both invoices</li>\n<li><strong>📞 Make contact</strong> to establish relationship and confirm payment processes</li>\n<li><strong>📝 Document preferred payment methods</strong> and any special terms</li>\n</ol>\n<h3><strong>Monitoring Schedule</strong> 📅</h3>\n<ul>\n<li><strong>March 23, 2026</strong>: Send friendly reminder for invoice #INV-00000005 (1 week before due)</li>\n<li><strong>March 30, 2026</strong>: Follow up on due date if payment not received</li>\n<li><strong>April 8, 2026</strong>: Send reminder for invoice #INV-00000006 (1 week before due)</li>\n</ul>\n<hr />\n<h2>🎖️ <strong>Client Relationship Strategy</strong></h2>\n<h3><strong>Build Strong Foundation</strong> 🏗️</h3>\n<ul>\n<li>Establish clear communication channels</li>\n<li>Confirm their preferred invoice format</li>\n<li>Set expectations for future payment terms</li>\n<li>Consider offering early payment discounts (2% if paid within 10 days)</li>\n</ul>\n<h3><strong>Red Flags to Watch</strong> 🚩</h3>\n<ul>\n<li>Late payment on first invoice (March 30th)</li>\n<li>Lack of response to communications</li>\n<li>Requests for extended payment terms without discussion</li>\n</ul>\n<hr />\n<h2>💡 <strong>Pro Tips for Success</strong></h2>\n<ol>\n<li><strong>First impressions matter</strong> - Be proactive but not pushy</li>\n<li><strong>Document everything</strong> - Start building their payment profile now</li>\n<li><strong>Stay positive</strong> - They\'re not overdue, so maintain good relationship</li>\n<li><strong>Be prepared</strong> - Have a collection process ready if needed</li>\n</ol>\n<p><strong>Next Review Date</strong>: April 1, 2026 📅</p>\n<p><em>Keep monitoring this client closely as they establish their payment pattern with your business!</em> 🎯</p>','2026-03-04 13:41:22','2026-03-04 13:41:22','2026-03-04 13:41:22');

/*!40000 ALTER TABLE `ai_insights` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table cache
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table cache_locks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table clients
# ------------------------------------------------------------

DROP TABLE IF EXISTS `clients`;

CREATE TABLE `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clients_user_id_foreign` (`user_id`),
  CONSTRAINT `clients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;

INSERT INTO `clients` (`id`, `user_id`, `name`, `email`, `phone`, `address`, `company`, `created_at`, `updated_at`)
VALUES
	(1,1,'San Miguel Corp','','','','','2026-03-03 03:18:59','2026-03-03 03:18:59'),
	(2,1,'Shell Philippines','','','','','2026-03-04 11:27:55','2026-03-04 11:27:55'),
	(3,1,'Puregold','','','','','2026-03-04 11:28:05','2026-03-04 11:28:05');

/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table failed_jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table invoice_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `invoice_items`;

CREATE TABLE `invoice_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '1.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  KEY `invoice_items_product_id_foreign` (`product_id`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `invoice_items` WRITE;
/*!40000 ALTER TABLE `invoice_items` DISABLE KEYS */;

INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `name`, `description`, `price`, `quantity`, `total`, `created_at`, `updated_at`)
VALUES
	(6,2,1,'Motolite Excel AGM','',39.00,1.00,39.00,'2026-03-04 11:33:59','2026-03-04 11:33:59'),
	(7,1,5,'Motolite Enduro','',65.00,1.00,65.00,'2026-03-04 11:34:02','2026-03-04 11:34:02'),
	(8,3,1,'Motolite Excel AGM','',39.00,1.00,39.00,'2026-03-04 12:57:49','2026-03-04 12:57:49'),
	(9,4,4,'Motolite Gold','',60.00,1.00,60.00,'2026-03-04 12:57:56','2026-03-04 12:57:56'),
	(10,5,5,'Motolite Enduro','',65.00,1.00,65.00,'2026-03-04 13:39:57','2026-03-04 13:39:57'),
	(11,6,3,'Motolite Excel','',55.00,3.00,165.00,'2026-03-04 13:40:41','2026-03-04 13:40:41');

/*!40000 ALTER TABLE `invoice_items` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table invoices
# ------------------------------------------------------------

DROP TABLE IF EXISTS `invoices`;

CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `invoice_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','sent','paid','overdue') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_user_id_foreign` (`user_id`),
  KEY `invoices_client_id_foreign` (`client_id`),
  CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;

INSERT INTO `invoices` (`id`, `user_id`, `client_id`, `invoice_number`, `status`, `issue_date`, `due_date`, `subtotal`, `tax_rate`, `tax_amount`, `total`, `notes`, `created_at`, `updated_at`)
VALUES
	(1,1,1,'INV-00000001','paid','2026-03-04','2026-04-03',65.00,0.00,0.00,65.00,'','2026-03-04 11:23:29','2026-03-04 11:52:43'),
	(2,1,2,'INV-00000002','sent','2026-03-04','2026-03-04',39.00,0.00,0.00,39.00,'','2026-03-04 11:28:22','2026-03-04 11:51:59'),
	(3,1,1,'INV-00000003','sent','2026-03-04','2026-04-03',39.00,0.00,0.00,39.00,'','2026-03-04 12:57:49','2026-03-04 12:58:00'),
	(4,1,1,'INV-00000004','sent','2026-03-04','2026-04-03',60.00,0.00,0.00,60.00,'','2026-03-04 12:57:56','2026-03-04 12:57:59'),
	(5,1,3,'INV-00000005','sent','2026-03-04','2026-03-30',65.00,0.00,0.00,65.00,'','2026-03-04 13:39:57','2026-03-04 13:40:48'),
	(6,1,3,'INV-00000006','sent','2026-03-04','2026-04-15',165.00,0.00,0.00,165.00,'','2026-03-04 13:40:41','2026-03-04 13:40:46');

/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table job_batches
# ------------------------------------------------------------

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'0001_01_01_000000_create_users_table',1),
	(2,'0001_01_01_000001_create_cache_table',1),
	(3,'0001_01_01_000002_create_jobs_table',1),
	(4,'2026_03_03_022952_create_clients_table',2),
	(5,'2026_03_04_094044_create_products_table',3),
	(6,'2026_03_04_094733_create_activity_log_table',4),
	(7,'2026_03_04_094734_add_event_column_to_activity_log_table',4),
	(8,'2026_03_04_094735_add_batch_uuid_column_to_activity_log_table',4),
	(9,'2026_03_04_094738_create_pulse_tables',5),
	(10,'2026_03_04_095323_create_invoices_table',6),
	(11,'2026_03_04_101852_create_invoice_items_table',6),
	(12,'2026_03_04_114846_create_payments_table',7),
	(13,'2026_03_04_131222_create_ai_insights_table',8);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table password_reset_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table payments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `payments`;

CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `invoice_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `method` enum('cash','bank_transfer','check','credit_card','online') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_user_id_foreign` (`user_id`),
  KEY `payments_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;

INSERT INTO `payments` (`id`, `user_id`, `invoice_id`, `amount`, `payment_date`, `method`, `reference`, `notes`, `created_at`, `updated_at`)
VALUES
	(1,1,1,40.00,'2026-03-04','cash','','','2026-03-04 11:52:12','2026-03-04 11:52:12');

/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pcs',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_user_id_foreign` (`user_id`),
  CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;

INSERT INTO `products` (`id`, `user_id`, `name`, `description`, `price`, `unit`, `created_at`, `updated_at`)
VALUES
	(1,1,'Motolite Excel AGM','',39.00,'pcs','2026-03-04 09:44:11','2026-03-04 09:44:37'),
	(2,1,'Motolite Excel EFB','',45.00,'pcs','2026-03-04 09:44:48','2026-03-04 09:44:48'),
	(3,1,'Motolite Excel','',55.00,'pcs','2026-03-04 09:45:04','2026-03-04 09:45:04'),
	(4,1,'Motolite Gold','',60.00,'pcs','2026-03-04 09:45:22','2026-03-04 09:45:22'),
	(5,1,'Motolite Enduro','',65.00,'pcs','2026-03-04 09:45:28','2026-03-04 09:45:28');

/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pulse_aggregates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pulse_aggregates`;

CREATE TABLE `pulse_aggregates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bucket` int unsigned NOT NULL,
  `period` mediumint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `key_hash` binary(16) GENERATED ALWAYS AS (unhex(md5(`key`))) VIRTUAL,
  `aggregate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(20,2) NOT NULL,
  `count` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pulse_aggregates_bucket_period_type_aggregate_key_hash_unique` (`bucket`,`period`,`type`,`aggregate`,`key_hash`),
  KEY `pulse_aggregates_period_bucket_index` (`period`,`bucket`),
  KEY `pulse_aggregates_type_index` (`type`),
  KEY `pulse_aggregates_period_type_aggregate_bucket_index` (`period`,`type`,`aggregate`,`bucket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `pulse_aggregates` WRITE;
/*!40000 ALTER TABLE `pulse_aggregates` DISABLE KEYS */;

INSERT INTO `pulse_aggregates` (`id`, `bucket`, `period`, `type`, `key`, `aggregate`, `value`, `count`)
VALUES
	(1,1772617680,60,'slow_request','[\"GET\",\"\\/zpanel\\/products\",\"App\\\\Livewire\\\\Products\\\\ProductList\"]','count',1.00,NULL),
	(2,1772617680,360,'slow_request','[\"GET\",\"\\/zpanel\\/products\",\"App\\\\Livewire\\\\Products\\\\ProductList\"]','count',1.00,NULL),
	(3,1772616960,1440,'slow_request','[\"GET\",\"\\/zpanel\\/products\",\"App\\\\Livewire\\\\Products\\\\ProductList\"]','count',1.00,NULL),
	(4,1772608320,10080,'slow_request','[\"GET\",\"\\/zpanel\\/products\",\"App\\\\Livewire\\\\Products\\\\ProductList\"]','count',1.00,NULL),
	(5,1772617680,60,'slow_user_request','1','count',1.00,NULL),
	(6,1772617680,360,'slow_user_request','1','count',1.00,NULL),
	(7,1772616960,1440,'slow_user_request','1','count',1.00,NULL),
	(8,1772608320,10080,'slow_user_request','1','count',1.00,NULL),
	(9,1772617680,60,'user_request','1','count',5.00,NULL),
	(10,1772617680,360,'user_request','1','count',8.00,NULL),
	(11,1772616960,1440,'user_request','1','count',8.00,NULL),
	(12,1772608320,10080,'user_request','1','count',8.00,NULL),
	(13,1772617680,60,'slow_request','[\"GET\",\"\\/zpanel\\/products\",\"App\\\\Livewire\\\\Products\\\\ProductList\"]','max',1924.00,NULL),
	(14,1772617680,360,'slow_request','[\"GET\",\"\\/zpanel\\/products\",\"App\\\\Livewire\\\\Products\\\\ProductList\"]','max',1924.00,NULL),
	(15,1772616960,1440,'slow_request','[\"GET\",\"\\/zpanel\\/products\",\"App\\\\Livewire\\\\Products\\\\ProductList\"]','max',1924.00,NULL),
	(16,1772608320,10080,'slow_request','[\"GET\",\"\\/zpanel\\/products\",\"App\\\\Livewire\\\\Products\\\\ProductList\"]','max',1924.00,NULL),
	(21,1772617680,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',2.00,NULL),
	(22,1772617680,360,'cache_miss','livewire-checksum-failures:127.0.0.1','count',4.00,NULL),
	(23,1772616960,1440,'cache_miss','livewire-checksum-failures:127.0.0.1','count',4.00,NULL),
	(24,1772608320,10080,'cache_miss','livewire-checksum-failures:127.0.0.1','count',4.00,NULL),
	(41,1772617740,60,'user_request','1','count',3.00,NULL),
	(42,1772617740,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',2.00,NULL),
	(61,1772622840,60,'user_request','1','count',7.00,NULL),
	(62,1772622720,360,'user_request','1','count',8.00,NULL),
	(63,1772622720,1440,'user_request','1','count',69.00,NULL),
	(64,1772618400,10080,'user_request','1','count',106.00,NULL),
	(65,1772622840,60,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/invoices\\/invoice-list.blade.php\"]','count',1.00,NULL),
	(66,1772622720,360,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/invoices\\/invoice-list.blade.php\"]','count',1.00,NULL),
	(67,1772622720,1440,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/invoices\\/invoice-list.blade.php\"]','count',1.00,NULL),
	(68,1772618400,10080,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/invoices\\/invoice-list.blade.php\"]','count',1.00,NULL),
	(73,1772622840,60,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/invoices\\/invoice-list.blade.php\"]','max',1772622849.00,NULL),
	(74,1772622720,360,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/invoices\\/invoice-list.blade.php\"]','max',1772622849.00,NULL),
	(75,1772622720,1440,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/invoices\\/invoice-list.blade.php\"]','max',1772622849.00,NULL),
	(76,1772618400,10080,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/invoices\\/invoice-list.blade.php\"]','max',1772622849.00,NULL),
	(85,1772622840,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',3.00,NULL),
	(86,1772622720,360,'cache_miss','livewire-checksum-failures:127.0.0.1','count',4.00,NULL),
	(87,1772622720,1440,'cache_miss','livewire-checksum-failures:127.0.0.1','count',26.00,NULL),
	(88,1772618400,10080,'cache_miss','livewire-checksum-failures:127.0.0.1','count',34.00,NULL),
	(109,1772622900,60,'user_request','1','count',1.00,NULL),
	(110,1772622900,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',1.00,NULL),
	(117,1772623200,60,'user_request','1','count',2.00,NULL),
	(118,1772623080,360,'user_request','1','count',11.00,NULL),
	(125,1772623320,60,'user_request','1','count',1.00,NULL),
	(129,1772623380,60,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/create\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(130,1772623080,360,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/create\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(131,1772622720,1440,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/create\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(132,1772618400,10080,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/create\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(133,1772623380,60,'slow_user_request','1','count',2.00,NULL),
	(134,1772623080,360,'slow_user_request','1','count',2.00,NULL),
	(135,1772622720,1440,'slow_user_request','1','count',2.00,NULL),
	(136,1772618400,10080,'slow_user_request','1','count',5.00,NULL),
	(137,1772623380,60,'user_request','1','count',8.00,NULL),
	(138,1772623380,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',4.00,NULL),
	(139,1772623080,360,'cache_miss','livewire-checksum-failures:127.0.0.1','count',4.00,NULL),
	(145,1772623380,60,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/create\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceCreate\"]','count',1.00,NULL),
	(146,1772623080,360,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/create\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceCreate\"]','count',1.00,NULL),
	(147,1772622720,1440,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/create\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceCreate\"]','count',1.00,NULL),
	(148,1772618400,10080,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/create\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceCreate\"]','count',1.00,NULL),
	(157,1772623380,60,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/create\",\"via \\/livewire-3a575798\\/update\"]','max',4986.00,NULL),
	(158,1772623080,360,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/create\",\"via \\/livewire-3a575798\\/update\"]','max',4986.00,NULL),
	(159,1772622720,1440,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/create\",\"via \\/livewire-3a575798\\/update\"]','max',4986.00,NULL),
	(160,1772618400,10080,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/create\",\"via \\/livewire-3a575798\\/update\"]','max',4986.00,NULL),
	(161,1772623380,60,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/create\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceCreate\"]','max',1656.00,NULL),
	(162,1772623080,360,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/create\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceCreate\"]','max',1656.00,NULL),
	(163,1772622720,1440,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/create\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceCreate\"]','max',1656.00,NULL),
	(164,1772618400,10080,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/create\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceCreate\"]','max',1656.00,NULL),
	(201,1772623500,60,'user_request','1','count',8.00,NULL),
	(202,1772623440,360,'user_request','1','count',29.00,NULL),
	(209,1772623500,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',2.00,NULL),
	(210,1772623440,360,'cache_miss','livewire-checksum-failures:127.0.0.1','count',12.00,NULL),
	(241,1772623620,60,'user_request','1','count',8.00,NULL),
	(261,1772623620,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',3.00,NULL),
	(285,1772623680,60,'slow_request','[\"GET\",\"\\/_debugbar\\/open\",\"Fruitcake\\\\LaravelDebugbar\\\\Controllers\\\\OpenHandlerController@handle\"]','count',1.00,NULL),
	(286,1772623440,360,'slow_request','[\"GET\",\"\\/_debugbar\\/open\",\"Fruitcake\\\\LaravelDebugbar\\\\Controllers\\\\OpenHandlerController@handle\"]','count',1.00,NULL),
	(287,1772622720,1440,'slow_request','[\"GET\",\"\\/_debugbar\\/open\",\"Fruitcake\\\\LaravelDebugbar\\\\Controllers\\\\OpenHandlerController@handle\"]','count',1.00,NULL),
	(288,1772618400,10080,'slow_request','[\"GET\",\"\\/_debugbar\\/open\",\"Fruitcake\\\\LaravelDebugbar\\\\Controllers\\\\OpenHandlerController@handle\"]','count',1.00,NULL),
	(289,1772623680,60,'slow_request','[\"GET\",\"\\/_debugbar\\/open\",\"Fruitcake\\\\LaravelDebugbar\\\\Controllers\\\\OpenHandlerController@handle\"]','max',3001.00,NULL),
	(290,1772623440,360,'slow_request','[\"GET\",\"\\/_debugbar\\/open\",\"Fruitcake\\\\LaravelDebugbar\\\\Controllers\\\\OpenHandlerController@handle\"]','max',3001.00,NULL),
	(291,1772622720,1440,'slow_request','[\"GET\",\"\\/_debugbar\\/open\",\"Fruitcake\\\\LaravelDebugbar\\\\Controllers\\\\OpenHandlerController@handle\"]','max',3001.00,NULL),
	(292,1772618400,10080,'slow_request','[\"GET\",\"\\/_debugbar\\/open\",\"Fruitcake\\\\LaravelDebugbar\\\\Controllers\\\\OpenHandlerController@handle\"]','max',3001.00,NULL),
	(293,1772623680,60,'user_request','1','count',13.00,NULL),
	(294,1772623680,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',7.00,NULL),
	(373,1772623800,60,'user_request','1','count',3.00,NULL),
	(374,1772623800,360,'user_request','1','count',21.00,NULL),
	(375,1772623800,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',2.00,NULL),
	(376,1772623800,360,'cache_miss','livewire-checksum-failures:127.0.0.1','count',6.00,NULL),
	(393,1772623860,60,'user_request','1','count',5.00,NULL),
	(401,1772623860,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',1.00,NULL),
	(417,1772623980,60,'user_request','1','count',6.00,NULL),
	(433,1772623980,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',1.00,NULL),
	(445,1772624040,60,'user_request','1','count',7.00,NULL),
	(449,1772624040,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',2.00,NULL),
	(481,1772624220,60,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceList\"]','count',1.00,NULL),
	(482,1772624160,360,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceList\"]','count',1.00,NULL),
	(483,1772624160,1440,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceList\"]','count',1.00,NULL),
	(484,1772618400,10080,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceList\"]','count',1.00,NULL),
	(485,1772624220,60,'slow_user_request','1','count',2.00,NULL),
	(486,1772624160,360,'slow_user_request','1','count',2.00,NULL),
	(487,1772624160,1440,'slow_user_request','1','count',2.00,NULL),
	(488,1772624220,60,'user_request','1','count',5.00,NULL),
	(489,1772624160,360,'user_request','1','count',6.00,NULL),
	(490,1772624160,1440,'user_request','1','count',33.00,NULL),
	(491,1772624220,60,'slow_query','[\"select * from `sessions` where `id` = ? limit 1\",\"vendor\\/livewire\\/livewire\\/src\\/Features\\/SupportDisablingBackButtonCache\\/DisableBackButtonCacheMiddleware.php:19\"]','count',1.00,NULL),
	(492,1772624160,360,'slow_query','[\"select * from `sessions` where `id` = ? limit 1\",\"vendor\\/livewire\\/livewire\\/src\\/Features\\/SupportDisablingBackButtonCache\\/DisableBackButtonCacheMiddleware.php:19\"]','count',1.00,NULL),
	(493,1772624160,1440,'slow_query','[\"select * from `sessions` where `id` = ? limit 1\",\"vendor\\/livewire\\/livewire\\/src\\/Features\\/SupportDisablingBackButtonCache\\/DisableBackButtonCacheMiddleware.php:19\"]','count',1.00,NULL),
	(494,1772618400,10080,'slow_query','[\"select * from `sessions` where `id` = ? limit 1\",\"vendor\\/livewire\\/livewire\\/src\\/Features\\/SupportDisablingBackButtonCache\\/DisableBackButtonCacheMiddleware.php:19\"]','count',1.00,NULL),
	(497,1772624220,60,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceList\"]','max',1377.00,NULL),
	(498,1772624160,360,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceList\"]','max',1377.00,NULL),
	(499,1772624160,1440,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceList\"]','max',1377.00,NULL),
	(500,1772618400,10080,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceList\"]','max',1377.00,NULL),
	(501,1772624220,60,'slow_query','[\"select * from `sessions` where `id` = ? limit 1\",\"vendor\\/livewire\\/livewire\\/src\\/Features\\/SupportDisablingBackButtonCache\\/DisableBackButtonCacheMiddleware.php:19\"]','max',1251.00,NULL),
	(502,1772624160,360,'slow_query','[\"select * from `sessions` where `id` = ? limit 1\",\"vendor\\/livewire\\/livewire\\/src\\/Features\\/SupportDisablingBackButtonCache\\/DisableBackButtonCacheMiddleware.php:19\"]','max',1251.00,NULL),
	(503,1772624160,1440,'slow_query','[\"select * from `sessions` where `id` = ? limit 1\",\"vendor\\/livewire\\/livewire\\/src\\/Features\\/SupportDisablingBackButtonCache\\/DisableBackButtonCacheMiddleware.php:19\"]','max',1251.00,NULL),
	(504,1772618400,10080,'slow_query','[\"select * from `sessions` where `id` = ? limit 1\",\"vendor\\/livewire\\/livewire\\/src\\/Features\\/SupportDisablingBackButtonCache\\/DisableBackButtonCacheMiddleware.php:19\"]','max',1251.00,NULL),
	(513,1772624220,60,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\\/pdf\\/view\",\"App\\\\Http\\\\Controllers\\\\InvoicePdfController@stream\"]','count',1.00,NULL),
	(514,1772624160,360,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\\/pdf\\/view\",\"App\\\\Http\\\\Controllers\\\\InvoicePdfController@stream\"]','count',1.00,NULL),
	(515,1772624160,1440,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\\/pdf\\/view\",\"App\\\\Http\\\\Controllers\\\\InvoicePdfController@stream\"]','count',1.00,NULL),
	(516,1772618400,10080,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\\/pdf\\/view\",\"App\\\\Http\\\\Controllers\\\\InvoicePdfController@stream\"]','count',1.00,NULL),
	(525,1772624220,60,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\\/pdf\\/view\",\"App\\\\Http\\\\Controllers\\\\InvoicePdfController@stream\"]','max',1354.00,NULL),
	(526,1772624160,360,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\\/pdf\\/view\",\"App\\\\Http\\\\Controllers\\\\InvoicePdfController@stream\"]','max',1354.00,NULL),
	(527,1772624160,1440,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\\/pdf\\/view\",\"App\\\\Http\\\\Controllers\\\\InvoicePdfController@stream\"]','max',1354.00,NULL),
	(528,1772618400,10080,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\\/pdf\\/view\",\"App\\\\Http\\\\Controllers\\\\InvoicePdfController@stream\"]','max',1354.00,NULL),
	(533,1772624280,60,'user_request','1','count',1.00,NULL),
	(537,1772624520,60,'user_request','1','count',2.00,NULL),
	(538,1772624520,360,'user_request','1','count',5.00,NULL),
	(545,1772624580,60,'user_request','1','count',3.00,NULL),
	(557,1772625060,60,'user_request','1','count',6.00,NULL),
	(558,1772624880,360,'user_request','1','count',22.00,NULL),
	(565,1772625060,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',3.00,NULL),
	(566,1772624880,360,'cache_miss','livewire-checksum-failures:127.0.0.1','count',7.00,NULL),
	(567,1772624160,1440,'cache_miss','livewire-checksum-failures:127.0.0.1','count',7.00,NULL),
	(593,1772625120,60,'user_request','1','count',14.00,NULL),
	(597,1772625120,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',4.00,NULL),
	(665,1772625180,60,'user_request','1','count',2.00,NULL),
	(673,1772628300,60,'user_request','1','count',4.00,NULL),
	(674,1772628120,360,'user_request','1','count',4.00,NULL),
	(675,1772627040,1440,'user_request','1','count',4.00,NULL),
	(685,1772628300,60,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(686,1772628120,360,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(687,1772627040,1440,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(688,1772618400,10080,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(689,1772628300,60,'slow_user_request','1','count',1.00,NULL),
	(690,1772628120,360,'slow_user_request','1','count',1.00,NULL),
	(691,1772627040,1440,'slow_user_request','1','count',1.00,NULL),
	(692,1772628300,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',1.00,NULL),
	(693,1772628120,360,'cache_miss','livewire-checksum-failures:127.0.0.1','count',1.00,NULL),
	(694,1772627040,1440,'cache_miss','livewire-checksum-failures:127.0.0.1','count',1.00,NULL),
	(701,1772628300,60,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]','max',8797.00,NULL),
	(702,1772628120,360,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]','max',8797.00,NULL),
	(703,1772627040,1440,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]','max',8797.00,NULL),
	(704,1772618400,10080,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]','max',8797.00,NULL),
	(705,1772628480,60,'user_request','1','count',2.00,NULL),
	(706,1772628480,360,'user_request','1','count',12.00,NULL),
	(707,1772628480,1440,'user_request','1','count',41.00,NULL),
	(708,1772628480,10080,'user_request','1','count',107.00,NULL),
	(709,1772628480,60,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(710,1772628480,360,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(711,1772628480,1440,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(712,1772628480,10080,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(713,1772628480,60,'slow_user_request','1','count',1.00,NULL),
	(714,1772628480,360,'slow_user_request','1','count',2.00,NULL),
	(715,1772628480,1440,'slow_user_request','1','count',3.00,NULL),
	(716,1772628480,10080,'slow_user_request','1','count',9.00,NULL),
	(717,1772628480,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',1.00,NULL),
	(718,1772628480,360,'cache_miss','livewire-checksum-failures:127.0.0.1','count',1.00,NULL),
	(719,1772628480,1440,'cache_miss','livewire-checksum-failures:127.0.0.1','count',9.00,NULL),
	(720,1772628480,10080,'cache_miss','livewire-checksum-failures:127.0.0.1','count',19.00,NULL),
	(725,1772628480,60,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]','max',10474.00,NULL),
	(726,1772628480,360,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]','max',10474.00,NULL),
	(727,1772628480,1440,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]','max',10474.00,NULL),
	(728,1772628480,10080,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]','max',10474.00,NULL),
	(729,1772628540,60,'user_request','1','count',2.00,NULL),
	(737,1772628600,60,'user_request','1','count',3.00,NULL),
	(749,1772628660,60,'user_request','1','count',4.00,NULL),
	(765,1772628720,60,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceShow\"]','count',1.00,NULL),
	(766,1772628480,360,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceShow\"]','count',1.00,NULL),
	(767,1772628480,1440,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceShow\"]','count',1.00,NULL),
	(768,1772628480,10080,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceShow\"]','count',1.00,NULL),
	(769,1772628720,60,'slow_user_request','1','count',1.00,NULL),
	(770,1772628720,60,'user_request','1','count',1.00,NULL),
	(777,1772628720,60,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceShow\"]','max',3122.00,NULL),
	(778,1772628480,360,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceShow\"]','max',3122.00,NULL),
	(779,1772628480,1440,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceShow\"]','max',3122.00,NULL),
	(780,1772628480,10080,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceShow\"]','max',3122.00,NULL),
	(781,1772629020,60,'user_request','1','count',10.00,NULL),
	(782,1772628840,360,'user_request','1','count',11.00,NULL),
	(789,1772629020,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',5.00,NULL),
	(790,1772628840,360,'cache_miss','livewire-checksum-failures:127.0.0.1','count',6.00,NULL),
	(841,1772629080,60,'user_request','1','count',1.00,NULL),
	(842,1772629080,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',1.00,NULL),
	(849,1772629200,60,'user_request','1','count',2.00,NULL),
	(850,1772629200,360,'user_request','1','count',14.00,NULL),
	(857,1772629260,60,'user_request','1','count',3.00,NULL),
	(858,1772629260,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',1.00,NULL),
	(859,1772629200,360,'cache_miss','livewire-checksum-failures:127.0.0.1','count',1.00,NULL),
	(860,1772629260,60,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/clients\\/client-list.blade.php\"]','count',1.00,NULL),
	(861,1772629200,360,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/clients\\/client-list.blade.php\"]','count',1.00,NULL),
	(862,1772628480,1440,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/clients\\/client-list.blade.php\"]','count',1.00,NULL),
	(863,1772628480,10080,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/clients\\/client-list.blade.php\"]','count',1.00,NULL),
	(869,1772629260,60,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/clients\\/client-list.blade.php\"]','max',1772629260.00,NULL),
	(870,1772629200,360,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/clients\\/client-list.blade.php\"]','max',1772629260.00,NULL),
	(871,1772628480,1440,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/clients\\/client-list.blade.php\"]','max',1772629260.00,NULL),
	(872,1772628480,10080,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/clients\\/client-list.blade.php\"]','max',1772629260.00,NULL),
	(881,1772629380,60,'user_request','1','count',2.00,NULL),
	(889,1772629440,60,'user_request','1','count',7.00,NULL),
	(913,1772629440,60,'exception','[\"Illuminate\\\\Database\\\\Eloquent\\\\RelationNotFoundException\",\"app\\/Livewire\\/Clients\\/ClientShow.php:15\"]','count',1.00,NULL),
	(914,1772629200,360,'exception','[\"Illuminate\\\\Database\\\\Eloquent\\\\RelationNotFoundException\",\"app\\/Livewire\\/Clients\\/ClientShow.php:15\"]','count',1.00,NULL),
	(915,1772628480,1440,'exception','[\"Illuminate\\\\Database\\\\Eloquent\\\\RelationNotFoundException\",\"app\\/Livewire\\/Clients\\/ClientShow.php:15\"]','count',1.00,NULL),
	(916,1772628480,10080,'exception','[\"Illuminate\\\\Database\\\\Eloquent\\\\RelationNotFoundException\",\"app\\/Livewire\\/Clients\\/ClientShow.php:15\"]','count',1.00,NULL),
	(921,1772629440,60,'exception','[\"Illuminate\\\\Database\\\\Eloquent\\\\RelationNotFoundException\",\"app\\/Livewire\\/Clients\\/ClientShow.php:15\"]','max',1772629468.00,NULL),
	(922,1772629200,360,'exception','[\"Illuminate\\\\Database\\\\Eloquent\\\\RelationNotFoundException\",\"app\\/Livewire\\/Clients\\/ClientShow.php:15\"]','max',1772629468.00,NULL),
	(923,1772628480,1440,'exception','[\"Illuminate\\\\Database\\\\Eloquent\\\\RelationNotFoundException\",\"app\\/Livewire\\/Clients\\/ClientShow.php:15\"]','max',1772629468.00,NULL),
	(924,1772628480,10080,'exception','[\"Illuminate\\\\Database\\\\Eloquent\\\\RelationNotFoundException\",\"app\\/Livewire\\/Clients\\/ClientShow.php:15\"]','max',1772629468.00,NULL),
	(925,1772629560,60,'user_request','1','count',1.00,NULL),
	(926,1772629560,360,'user_request','1','count',4.00,NULL),
	(927,1772629560,60,'exception','[\"ParseError\",\"resources\\/views\\/livewire\\/clients\\/client-show.blade.php\"]','count',1.00,NULL),
	(928,1772629560,360,'exception','[\"ParseError\",\"resources\\/views\\/livewire\\/clients\\/client-show.blade.php\"]','count',1.00,NULL),
	(929,1772628480,1440,'exception','[\"ParseError\",\"resources\\/views\\/livewire\\/clients\\/client-show.blade.php\"]','count',1.00,NULL),
	(930,1772628480,10080,'exception','[\"ParseError\",\"resources\\/views\\/livewire\\/clients\\/client-show.blade.php\"]','count',1.00,NULL),
	(933,1772629560,60,'exception','[\"ParseError\",\"resources\\/views\\/livewire\\/clients\\/client-show.blade.php\"]','max',1772629605.00,NULL),
	(934,1772629560,360,'exception','[\"ParseError\",\"resources\\/views\\/livewire\\/clients\\/client-show.blade.php\"]','max',1772629605.00,NULL),
	(935,1772628480,1440,'exception','[\"ParseError\",\"resources\\/views\\/livewire\\/clients\\/client-show.blade.php\"]','max',1772629605.00,NULL),
	(936,1772628480,10080,'exception','[\"ParseError\",\"resources\\/views\\/livewire\\/clients\\/client-show.blade.php\"]','max',1772629605.00,NULL),
	(937,1772629680,60,'user_request','1','count',2.00,NULL),
	(941,1772629680,60,'slow_request','[\"POST\",\"\\/clients\\/1\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(942,1772629560,360,'slow_request','[\"POST\",\"\\/clients\\/1\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(943,1772628480,1440,'slow_request','[\"POST\",\"\\/clients\\/1\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(944,1772628480,10080,'slow_request','[\"POST\",\"\\/clients\\/1\",\"via \\/livewire-3a575798\\/update\"]','count',2.00,NULL),
	(945,1772629680,60,'slow_user_request','1','count',1.00,NULL),
	(946,1772629560,360,'slow_user_request','1','count',1.00,NULL),
	(947,1772629680,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',1.00,NULL),
	(948,1772629560,360,'cache_miss','livewire-checksum-failures:127.0.0.1','count',1.00,NULL),
	(957,1772629680,60,'slow_request','[\"POST\",\"\\/clients\\/1\",\"via \\/livewire-3a575798\\/update\"]','max',14750.00,NULL),
	(958,1772629560,360,'slow_request','[\"POST\",\"\\/clients\\/1\",\"via \\/livewire-3a575798\\/update\"]','max',14750.00,NULL),
	(959,1772628480,1440,'slow_request','[\"POST\",\"\\/clients\\/1\",\"via \\/livewire-3a575798\\/update\"]','max',14750.00,NULL),
	(960,1772628480,10080,'slow_request','[\"POST\",\"\\/clients\\/1\",\"via \\/livewire-3a575798\\/update\"]','max',14955.00,NULL),
	(961,1772629800,60,'user_request','1','count',1.00,NULL),
	(965,1772630400,60,'user_request','1','count',10.00,NULL),
	(966,1772630280,360,'user_request','1','count',21.00,NULL),
	(967,1772629920,1440,'user_request','1','count',39.00,NULL),
	(977,1772630400,60,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/4\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(978,1772630280,360,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/4\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(979,1772629920,1440,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/4\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(980,1772628480,10080,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/4\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(981,1772630400,60,'slow_user_request','1','count',2.00,NULL),
	(982,1772630280,360,'slow_user_request','1','count',2.00,NULL),
	(983,1772629920,1440,'slow_user_request','1','count',3.00,NULL),
	(984,1772630400,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',2.00,NULL),
	(985,1772630280,360,'cache_miss','livewire-checksum-failures:127.0.0.1','count',2.00,NULL),
	(986,1772629920,1440,'cache_miss','livewire-checksum-failures:127.0.0.1','count',3.00,NULL),
	(993,1772630400,60,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/4\",\"via \\/livewire-3a575798\\/update\"]','max',10026.00,NULL),
	(994,1772630280,360,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/4\",\"via \\/livewire-3a575798\\/update\"]','max',10026.00,NULL),
	(995,1772629920,1440,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/4\",\"via \\/livewire-3a575798\\/update\"]','max',10026.00,NULL),
	(996,1772628480,10080,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/4\",\"via \\/livewire-3a575798\\/update\"]','max',10026.00,NULL),
	(1017,1772630400,60,'slow_request','[\"POST\",\"\\/clients\\/1\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(1018,1772630280,360,'slow_request','[\"POST\",\"\\/clients\\/1\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(1019,1772629920,1440,'slow_request','[\"POST\",\"\\/clients\\/1\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(1033,1772630400,60,'slow_request','[\"POST\",\"\\/clients\\/1\",\"via \\/livewire-3a575798\\/update\"]','max',14955.00,NULL),
	(1034,1772630280,360,'slow_request','[\"POST\",\"\\/clients\\/1\",\"via \\/livewire-3a575798\\/update\"]','max',14955.00,NULL),
	(1035,1772629920,1440,'slow_request','[\"POST\",\"\\/clients\\/1\",\"via \\/livewire-3a575798\\/update\"]','max',14955.00,NULL),
	(1037,1772630460,60,'user_request','1','count',4.00,NULL),
	(1053,1772630520,60,'user_request','1','count',6.00,NULL),
	(1077,1772630580,60,'user_request','1','count',1.00,NULL),
	(1081,1772630940,60,'user_request','1','count',4.00,NULL),
	(1082,1772630640,360,'user_request','1','count',4.00,NULL),
	(1093,1772630940,60,'slow_request','[\"POST\",\"\\/reports\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(1094,1772630640,360,'slow_request','[\"POST\",\"\\/reports\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(1095,1772629920,1440,'slow_request','[\"POST\",\"\\/reports\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(1096,1772628480,10080,'slow_request','[\"POST\",\"\\/reports\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(1097,1772630940,60,'slow_user_request','1','count',1.00,NULL),
	(1098,1772630640,360,'slow_user_request','1','count',1.00,NULL),
	(1099,1772630940,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',1.00,NULL),
	(1100,1772630640,360,'cache_miss','livewire-checksum-failures:127.0.0.1','count',1.00,NULL),
	(1109,1772630940,60,'slow_request','[\"POST\",\"\\/reports\",\"via \\/livewire-3a575798\\/update\"]','max',21529.00,NULL),
	(1110,1772630640,360,'slow_request','[\"POST\",\"\\/reports\",\"via \\/livewire-3a575798\\/update\"]','max',21529.00,NULL),
	(1111,1772629920,1440,'slow_request','[\"POST\",\"\\/reports\",\"via \\/livewire-3a575798\\/update\"]','max',21529.00,NULL),
	(1112,1772628480,10080,'slow_request','[\"POST\",\"\\/reports\",\"via \\/livewire-3a575798\\/update\"]','max',21529.00,NULL),
	(1113,1772631000,60,'user_request','1','count',2.00,NULL),
	(1114,1772631000,360,'user_request','1','count',14.00,NULL),
	(1121,1772631060,60,'user_request','1','count',11.00,NULL),
	(1165,1772631120,60,'user_request','1','count',1.00,NULL),
	(1169,1772631480,60,'user_request','1','count',5.00,NULL),
	(1170,1772631360,360,'user_request','1','count',24.00,NULL),
	(1171,1772631360,1440,'user_request','1','count',27.00,NULL),
	(1189,1772631540,60,'user_request','1','count',5.00,NULL),
	(1193,1772631540,60,'slow_request','[\"POST\",\"\\/invoices\\/ai-draft\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(1194,1772631360,360,'slow_request','[\"POST\",\"\\/invoices\\/ai-draft\",\"via \\/livewire-3a575798\\/update\"]','count',2.00,NULL),
	(1195,1772631360,1440,'slow_request','[\"POST\",\"\\/invoices\\/ai-draft\",\"via \\/livewire-3a575798\\/update\"]','count',2.00,NULL),
	(1196,1772628480,10080,'slow_request','[\"POST\",\"\\/invoices\\/ai-draft\",\"via \\/livewire-3a575798\\/update\"]','count',2.00,NULL),
	(1197,1772631540,60,'slow_user_request','1','count',1.00,NULL),
	(1198,1772631360,360,'slow_user_request','1','count',3.00,NULL),
	(1199,1772631360,1440,'slow_user_request','1','count',3.00,NULL),
	(1200,1772631540,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',2.00,NULL),
	(1201,1772631360,360,'cache_miss','livewire-checksum-failures:127.0.0.1','count',7.00,NULL),
	(1202,1772631360,1440,'cache_miss','livewire-checksum-failures:127.0.0.1','count',7.00,NULL),
	(1209,1772631540,60,'slow_request','[\"POST\",\"\\/invoices\\/ai-draft\",\"via \\/livewire-3a575798\\/update\"]','max',3832.00,NULL),
	(1210,1772631360,360,'slow_request','[\"POST\",\"\\/invoices\\/ai-draft\",\"via \\/livewire-3a575798\\/update\"]','max',4031.00,NULL),
	(1211,1772631360,1440,'slow_request','[\"POST\",\"\\/invoices\\/ai-draft\",\"via \\/livewire-3a575798\\/update\"]','max',4031.00,NULL),
	(1212,1772628480,10080,'slow_request','[\"POST\",\"\\/invoices\\/ai-draft\",\"via \\/livewire-3a575798\\/update\"]','max',4031.00,NULL),
	(1229,1772631600,60,'user_request','1','count',11.00,NULL),
	(1249,1772631600,60,'slow_request','[\"POST\",\"\\/invoices\\/ai-draft\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(1250,1772631600,60,'slow_user_request','1','count',1.00,NULL),
	(1251,1772631600,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',4.00,NULL),
	(1265,1772631600,60,'slow_request','[\"POST\",\"\\/invoices\\/ai-draft\",\"via \\/livewire-3a575798\\/update\"]','max',4031.00,NULL),
	(1301,1772631660,60,'user_request','1','count',3.00,NULL),
	(1309,1772631660,60,'slow_request','[\"POST\",\"\\/clients\\/3\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(1310,1772631360,360,'slow_request','[\"POST\",\"\\/clients\\/3\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(1311,1772631360,1440,'slow_request','[\"POST\",\"\\/clients\\/3\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(1312,1772628480,10080,'slow_request','[\"POST\",\"\\/clients\\/3\",\"via \\/livewire-3a575798\\/update\"]','count',1.00,NULL),
	(1313,1772631660,60,'slow_user_request','1','count',1.00,NULL),
	(1314,1772631660,60,'cache_miss','livewire-checksum-failures:127.0.0.1','count',1.00,NULL),
	(1325,1772631660,60,'slow_request','[\"POST\",\"\\/clients\\/3\",\"via \\/livewire-3a575798\\/update\"]','max',15217.00,NULL),
	(1326,1772631360,360,'slow_request','[\"POST\",\"\\/clients\\/3\",\"via \\/livewire-3a575798\\/update\"]','max',15217.00,NULL),
	(1327,1772631360,1440,'slow_request','[\"POST\",\"\\/clients\\/3\",\"via \\/livewire-3a575798\\/update\"]','max',15217.00,NULL),
	(1328,1772628480,10080,'slow_request','[\"POST\",\"\\/clients\\/3\",\"via \\/livewire-3a575798\\/update\"]','max',15217.00,NULL),
	(1329,1772631720,60,'user_request','1','count',3.00,NULL),
	(1330,1772631720,360,'user_request','1','count',3.00,NULL);

/*!40000 ALTER TABLE `pulse_aggregates` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pulse_entries
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pulse_entries`;

CREATE TABLE `pulse_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` int unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `key_hash` binary(16) GENERATED ALWAYS AS (unhex(md5(`key`))) VIRTUAL,
  `value` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pulse_entries_timestamp_index` (`timestamp`),
  KEY `pulse_entries_type_index` (`type`),
  KEY `pulse_entries_key_hash_index` (`key_hash`),
  KEY `pulse_entries_timestamp_type_key_hash_value_index` (`timestamp`,`type`,`key_hash`,`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `pulse_entries` WRITE;
/*!40000 ALTER TABLE `pulse_entries` DISABLE KEYS */;

INSERT INTO `pulse_entries` (`id`, `timestamp`, `type`, `key`, `value`)
VALUES
	(1,1772617705,'slow_request','[\"GET\",\"\\/zpanel\\/products\",\"App\\\\Livewire\\\\Products\\\\ProductList\"]',1924),
	(2,1772617705,'slow_user_request','1',NULL),
	(3,1772617705,'user_request','1',NULL),
	(4,1772617712,'user_request','1',NULL),
	(5,1772617716,'user_request','1',NULL),
	(6,1772617716,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(7,1772617720,'user_request','1',NULL),
	(8,1772617720,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(9,1772617739,'user_request','1',NULL),
	(10,1772617741,'user_request','1',NULL),
	(11,1772617741,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(12,1772617742,'user_request','1',NULL),
	(13,1772617742,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(14,1772617754,'user_request','1',NULL),
	(15,1772622846,'user_request','1',NULL),
	(16,1772622849,'user_request','1',NULL),
	(17,1772622849,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/invoices\\/invoice-list.blade.php\"]',1772622849),
	(18,1772622858,'user_request','1',NULL),
	(19,1772622861,'user_request','1',NULL),
	(20,1772622874,'user_request','1',NULL),
	(21,1772622874,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(22,1772622893,'user_request','1',NULL),
	(23,1772622893,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(24,1772622899,'user_request','1',NULL),
	(25,1772622899,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(26,1772622906,'user_request','1',NULL),
	(27,1772622906,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(28,1772623205,'user_request','1',NULL),
	(29,1772623221,'user_request','1',NULL),
	(30,1772623369,'user_request','1',NULL),
	(31,1772623381,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/create\",\"via \\/livewire-3a575798\\/update\"]',4986),
	(32,1772623381,'slow_user_request','1',NULL),
	(33,1772623381,'user_request','1',NULL),
	(34,1772623386,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(35,1772623385,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/create\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceCreate\"]',1656),
	(36,1772623385,'slow_user_request','1',NULL),
	(37,1772623385,'user_request','1',NULL),
	(38,1772623393,'user_request','1',NULL),
	(39,1772623393,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(40,1772623404,'user_request','1',NULL),
	(41,1772623405,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(42,1772623408,'user_request','1',NULL),
	(43,1772623409,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(44,1772623409,'user_request','1',NULL),
	(45,1772623415,'user_request','1',NULL),
	(46,1772623417,'user_request','1',NULL),
	(47,1772623506,'user_request','1',NULL),
	(48,1772623507,'user_request','1',NULL),
	(49,1772623512,'user_request','1',NULL),
	(50,1772623512,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(51,1772623514,'user_request','1',NULL),
	(52,1772623514,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(53,1772623514,'user_request','1',NULL),
	(54,1772623515,'user_request','1',NULL),
	(55,1772623523,'user_request','1',NULL),
	(56,1772623543,'user_request','1',NULL),
	(57,1772623634,'user_request','1',NULL),
	(58,1772623635,'user_request','1',NULL),
	(59,1772623640,'user_request','1',NULL),
	(60,1772623646,'user_request','1',NULL),
	(61,1772623663,'user_request','1',NULL),
	(62,1772623664,'user_request','1',NULL),
	(63,1772623664,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(64,1772623674,'user_request','1',NULL),
	(65,1772623674,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(66,1772623676,'user_request','1',NULL),
	(67,1772623677,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(68,1772623680,'slow_request','[\"GET\",\"\\/_debugbar\\/open\",\"Fruitcake\\\\LaravelDebugbar\\\\Controllers\\\\OpenHandlerController@handle\"]',3001),
	(69,1772623685,'user_request','1',NULL),
	(70,1772623685,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(71,1772623686,'user_request','1',NULL),
	(72,1772623686,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(73,1772623688,'user_request','1',NULL),
	(74,1772623688,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(75,1772623689,'user_request','1',NULL),
	(76,1772623690,'user_request','1',NULL),
	(77,1772623692,'user_request','1',NULL),
	(78,1772623700,'user_request','1',NULL),
	(79,1772623700,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(80,1772623702,'user_request','1',NULL),
	(81,1772623702,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(82,1772623702,'user_request','1',NULL),
	(83,1772623707,'user_request','1',NULL),
	(84,1772623711,'user_request','1',NULL),
	(85,1772623711,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(86,1772623711,'user_request','1',NULL),
	(87,1772623714,'user_request','1',NULL),
	(88,1772623714,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(89,1772623813,'user_request','1',NULL),
	(90,1772623813,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(91,1772623813,'user_request','1',NULL),
	(92,1772623813,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(93,1772623831,'user_request','1',NULL),
	(94,1772623880,'user_request','1',NULL),
	(95,1772623884,'user_request','1',NULL),
	(96,1772623893,'user_request','1',NULL),
	(97,1772623893,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(98,1772623893,'user_request','1',NULL),
	(99,1772623898,'user_request','1',NULL),
	(100,1772624012,'user_request','1',NULL),
	(101,1772624013,'user_request','1',NULL),
	(102,1772624033,'user_request','1',NULL),
	(103,1772624034,'user_request','1',NULL),
	(104,1772624038,'user_request','1',NULL),
	(105,1772624039,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(106,1772624039,'user_request','1',NULL),
	(107,1772624040,'user_request','1',NULL),
	(108,1772624042,'user_request','1',NULL),
	(109,1772624042,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(110,1772624042,'user_request','1',NULL),
	(111,1772624044,'user_request','1',NULL),
	(112,1772624052,'user_request','1',NULL),
	(113,1772624057,'user_request','1',NULL),
	(114,1772624058,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(115,1772624060,'user_request','1',NULL),
	(116,1772624244,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceList\"]',1377),
	(117,1772624244,'slow_user_request','1',NULL),
	(118,1772624244,'user_request','1',NULL),
	(119,1772624244,'slow_query','[\"select * from `sessions` where `id` = ? limit 1\",\"vendor\\/livewire\\/livewire\\/src\\/Features\\/SupportDisablingBackButtonCache\\/DisableBackButtonCacheMiddleware.php:19\"]',1251),
	(120,1772624247,'user_request','1',NULL),
	(121,1772624251,'user_request','1',NULL),
	(122,1772624257,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\\/pdf\\/view\",\"App\\\\Http\\\\Controllers\\\\InvoicePdfController@stream\"]',1354),
	(123,1772624257,'slow_user_request','1',NULL),
	(124,1772624257,'user_request','1',NULL),
	(125,1772624265,'user_request','1',NULL),
	(126,1772624291,'user_request','1',NULL),
	(127,1772624555,'user_request','1',NULL),
	(128,1772624556,'user_request','1',NULL),
	(129,1772624602,'user_request','1',NULL),
	(130,1772624609,'user_request','1',NULL),
	(131,1772624610,'user_request','1',NULL),
	(132,1772625102,'user_request','1',NULL),
	(133,1772625104,'user_request','1',NULL),
	(134,1772625106,'user_request','1',NULL),
	(135,1772625106,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(136,1772625115,'user_request','1',NULL),
	(137,1772625115,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(138,1772625116,'user_request','1',NULL),
	(139,1772625119,'user_request','1',NULL),
	(140,1772625119,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(141,1772625121,'user_request','1',NULL),
	(142,1772625121,'user_request','1',NULL),
	(143,1772625121,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(144,1772625132,'user_request','1',NULL),
	(145,1772625132,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(146,1772625135,'user_request','1',NULL),
	(147,1772625138,'user_request','1',NULL),
	(148,1772625141,'user_request','1',NULL),
	(149,1772625144,'user_request','1',NULL),
	(150,1772625145,'user_request','1',NULL),
	(151,1772625146,'user_request','1',NULL),
	(152,1772625152,'user_request','1',NULL),
	(153,1772625156,'user_request','1',NULL),
	(154,1772625156,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(155,1772625163,'user_request','1',NULL),
	(156,1772625163,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(157,1772625166,'user_request','1',NULL),
	(158,1772625171,'user_request','1',NULL),
	(159,1772625211,'user_request','1',NULL),
	(160,1772625215,'user_request','1',NULL),
	(161,1772628317,'user_request','1',NULL),
	(162,1772628319,'user_request','1',NULL),
	(163,1772628324,'user_request','1',NULL),
	(164,1772628329,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]',8797),
	(165,1772628329,'slow_user_request','1',NULL),
	(166,1772628329,'user_request','1',NULL),
	(167,1772628329,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(168,1772628521,'user_request','1',NULL),
	(169,1772628523,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/2\",\"via \\/livewire-3a575798\\/update\"]',10474),
	(170,1772628523,'slow_user_request','1',NULL),
	(171,1772628523,'user_request','1',NULL),
	(172,1772628523,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(173,1772628561,'user_request','1',NULL),
	(174,1772628565,'user_request','1',NULL),
	(175,1772628642,'user_request','1',NULL),
	(176,1772628645,'user_request','1',NULL),
	(177,1772628649,'user_request','1',NULL),
	(178,1772628688,'user_request','1',NULL),
	(179,1772628709,'user_request','1',NULL),
	(180,1772628715,'user_request','1',NULL),
	(181,1772628717,'user_request','1',NULL),
	(182,1772628743,'slow_request','[\"GET\",\"\\/zpanel\\/invoices\\/{invoice}\",\"App\\\\Livewire\\\\Invoices\\\\InvoiceShow\"]',3122),
	(183,1772628743,'slow_user_request','1',NULL),
	(184,1772628743,'user_request','1',NULL),
	(185,1772629054,'user_request','1',NULL),
	(186,1772629059,'user_request','1',NULL),
	(187,1772629067,'user_request','1',NULL),
	(188,1772629067,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(189,1772629069,'user_request','1',NULL),
	(190,1772629069,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(191,1772629069,'user_request','1',NULL),
	(192,1772629071,'user_request','1',NULL),
	(193,1772629075,'user_request','1',NULL),
	(194,1772629075,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(195,1772629076,'user_request','1',NULL),
	(196,1772629076,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(197,1772629076,'user_request','1',NULL),
	(198,1772629079,'user_request','1',NULL),
	(199,1772629079,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(200,1772629080,'user_request','1',NULL),
	(201,1772629080,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(202,1772629213,'user_request','1',NULL),
	(203,1772629247,'user_request','1',NULL),
	(204,1772629260,'user_request','1',NULL),
	(205,1772629260,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(206,1772629260,'exception','[\"Symfony\\\\Component\\\\Routing\\\\Exception\\\\RouteNotFoundException\",\"resources\\/views\\/livewire\\/clients\\/client-list.blade.php\"]',1772629260),
	(207,1772629297,'user_request','1',NULL),
	(208,1772629298,'user_request','1',NULL),
	(209,1772629387,'user_request','1',NULL),
	(210,1772629391,'user_request','1',NULL),
	(211,1772629450,'user_request','1',NULL),
	(212,1772629453,'user_request','1',NULL),
	(213,1772629454,'user_request','1',NULL),
	(214,1772629457,'user_request','1',NULL),
	(215,1772629457,'user_request','1',NULL),
	(216,1772629464,'user_request','1',NULL),
	(217,1772629468,'user_request','1',NULL),
	(218,1772629468,'exception','[\"Illuminate\\\\Database\\\\Eloquent\\\\RelationNotFoundException\",\"app\\/Livewire\\/Clients\\/ClientShow.php:15\"]',1772629468),
	(219,1772629605,'user_request','1',NULL),
	(220,1772629605,'exception','[\"ParseError\",\"resources\\/views\\/livewire\\/clients\\/client-show.blade.php\"]',1772629605),
	(221,1772629695,'user_request','1',NULL),
	(222,1772629701,'slow_request','[\"POST\",\"\\/clients\\/1\",\"via \\/livewire-3a575798\\/update\"]',14750),
	(223,1772629701,'slow_user_request','1',NULL),
	(224,1772629701,'user_request','1',NULL),
	(225,1772629701,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(226,1772629837,'user_request','1',NULL),
	(227,1772630403,'user_request','1',NULL),
	(228,1772630406,'user_request','1',NULL),
	(229,1772630410,'user_request','1',NULL),
	(230,1772630413,'slow_request','[\"POST\",\"\\/zpanel\\/invoices\\/4\",\"via \\/livewire-3a575798\\/update\"]',10026),
	(231,1772630413,'slow_user_request','1',NULL),
	(232,1772630413,'user_request','1',NULL),
	(233,1772630413,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(234,1772630436,'user_request','1',NULL),
	(235,1772630438,'user_request','1',NULL),
	(236,1772630440,'user_request','1',NULL),
	(237,1772630441,'user_request','1',NULL),
	(238,1772630443,'user_request','1',NULL),
	(239,1772630445,'slow_request','[\"POST\",\"\\/clients\\/1\",\"via \\/livewire-3a575798\\/update\"]',14955),
	(240,1772630445,'slow_user_request','1',NULL),
	(241,1772630445,'user_request','1',NULL),
	(242,1772630445,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(243,1772630467,'user_request','1',NULL),
	(244,1772630468,'user_request','1',NULL),
	(245,1772630487,'user_request','1',NULL),
	(246,1772630488,'user_request','1',NULL),
	(247,1772630558,'user_request','1',NULL),
	(248,1772630559,'user_request','1',NULL),
	(249,1772630561,'user_request','1',NULL),
	(250,1772630567,'user_request','1',NULL),
	(251,1772630568,'user_request','1',NULL),
	(252,1772630571,'user_request','1',NULL),
	(253,1772630627,'user_request','1',NULL),
	(254,1772630968,'user_request','1',NULL),
	(255,1772630969,'user_request','1',NULL),
	(256,1772630973,'user_request','1',NULL),
	(257,1772630978,'slow_request','[\"POST\",\"\\/reports\",\"via \\/livewire-3a575798\\/update\"]',21529),
	(258,1772630978,'slow_user_request','1',NULL),
	(259,1772630978,'user_request','1',NULL),
	(260,1772630978,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(261,1772631009,'user_request','1',NULL),
	(262,1772631010,'user_request','1',NULL),
	(263,1772631074,'user_request','1',NULL),
	(264,1772631077,'user_request','1',NULL),
	(265,1772631096,'user_request','1',NULL),
	(266,1772631102,'user_request','1',NULL),
	(267,1772631105,'user_request','1',NULL),
	(268,1772631105,'user_request','1',NULL),
	(269,1772631105,'user_request','1',NULL),
	(270,1772631108,'user_request','1',NULL),
	(271,1772631111,'user_request','1',NULL),
	(272,1772631111,'user_request','1',NULL),
	(273,1772631119,'user_request','1',NULL),
	(274,1772631128,'user_request','1',NULL),
	(275,1772631520,'user_request','1',NULL),
	(276,1772631523,'user_request','1',NULL),
	(277,1772631532,'user_request','1',NULL),
	(278,1772631536,'user_request','1',NULL),
	(279,1772631538,'user_request','1',NULL),
	(280,1772631540,'user_request','1',NULL),
	(281,1772631566,'slow_request','[\"POST\",\"\\/invoices\\/ai-draft\",\"via \\/livewire-3a575798\\/update\"]',3832),
	(282,1772631566,'slow_user_request','1',NULL),
	(283,1772631566,'user_request','1',NULL),
	(284,1772631566,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(285,1772631584,'user_request','1',NULL),
	(286,1772631596,'user_request','1',NULL),
	(287,1772631597,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(288,1772631597,'user_request','1',NULL),
	(289,1772631600,'user_request','1',NULL),
	(290,1772631604,'user_request','1',NULL),
	(291,1772631608,'user_request','1',NULL),
	(292,1772631612,'user_request','1',NULL),
	(293,1772631613,'user_request','1',NULL),
	(294,1772631631,'slow_request','[\"POST\",\"\\/invoices\\/ai-draft\",\"via \\/livewire-3a575798\\/update\"]',4031),
	(295,1772631631,'slow_user_request','1',NULL),
	(296,1772631631,'user_request','1',NULL),
	(297,1772631632,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(298,1772631641,'user_request','1',NULL),
	(299,1772631641,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(300,1772631641,'user_request','1',NULL),
	(301,1772631646,'user_request','1',NULL),
	(302,1772631646,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(303,1772631648,'user_request','1',NULL),
	(304,1772631648,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(305,1772631652,'user_request','1',NULL),
	(306,1772631663,'user_request','1',NULL),
	(307,1772631665,'user_request','1',NULL),
	(308,1772631666,'slow_request','[\"POST\",\"\\/clients\\/3\",\"via \\/livewire-3a575798\\/update\"]',15217),
	(309,1772631666,'slow_user_request','1',NULL),
	(310,1772631666,'user_request','1',NULL),
	(311,1772631667,'cache_miss','livewire-checksum-failures:127.0.0.1',NULL),
	(312,1772631726,'user_request','1',NULL),
	(313,1772631728,'user_request','1',NULL),
	(314,1772631764,'user_request','1',NULL);

/*!40000 ALTER TABLE `pulse_entries` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pulse_values
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pulse_values`;

CREATE TABLE `pulse_values` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` int unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `key_hash` binary(16) GENERATED ALWAYS AS (unhex(md5(`key`))) VIRTUAL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pulse_values_type_key_hash_unique` (`type`,`key_hash`),
  KEY `pulse_values_timestamp_index` (`timestamp`),
  KEY `pulse_values_type_index` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`)
VALUES
	('X1ViPPDXRjyiHtixwoDLlg55iGSuMx5SH8QEPHTi',1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiR3kxU2VjWUl4ZnVQUGh4UUp4UXhyelZ6S1dqam9FcG1EUlBmd0JacCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9pbnZvaWNpby50ZXN0L3pwYW5lbC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1772631764);

/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`)
VALUES
	(1,'Reg','admin@ziontek.co',NULL,'$2y$12$xlXqJnEYS624IsR4LBSp7.jGKyiYpLjVE5ltbD3GmYv4E93SOgqfG',NULL,'2026-03-03 02:21:19','2026-03-03 02:21:19');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
