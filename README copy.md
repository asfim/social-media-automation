# AI Social Media Automation & Business Assistant

## Complete Laravel Project Development Prompt

Build a complete production-ready **AI Social Media Automation & Business Assistant** web application using:

* Laravel 12+
* PHP 8.3+
* MySQL 8+
* Bootstrap 5.3
* Blade Template
* JavaScript / AJAX / Fetch API
* Laravel Queue
* Laravel Scheduler
* Laravel Notifications
* REST API
* Meta Graph API integration
* Facebook Webhooks
* Instagram integration where supported by Meta API
* OpenAI-compatible AI API integration
* Font Awesome / Bootstrap Icons
* Chart.js for analytics

This is **NOT a SaaS application**.

This is a **single-business automation system** for one business/company to manage its Facebook Page, Instagram account, customer messages, comments, AI replies, leads and business knowledge from one admin panel.

The system must be modular, clean, secure, responsive and production-ready.

---

# 1. UI / DESIGN REQUIREMENTS

Create a modern, professional AI SaaS-style dashboard design even though this is a single-business application.

The design should be inspired by modern AI customer-support and social-media automation dashboards.

## Overall Design

* Clean modern UI
* Premium professional appearance
* Responsive
* Desktop-first but fully mobile responsive
* Light mode as default
* White/very light gray background
* Dark navy/black text
* Blue/purple AI accent elements
* Rounded cards
* Soft shadows
* Minimal borders
* Modern typography
* Clean spacing
* Professional dashboard widgets
* No excessive gradients
* No cartoonish AI graphics
* Avoid an obviously AI-generated visual style

Use Bootstrap 5.3 components but customize them heavily with CSS.

---

# 2. MAIN LAYOUT

Create:

```text
Sidebar
Top Navbar
Main Content
```

Desktop:

```text
┌──────────────────────────────────────────────────────────────┐
│ Sidebar │ Top Navbar                                         │
│         ├────────────────────────────────────────────────────┤
│         │                                                    │
│         │              Main Dashboard Content               │
│         │                                                    │
│         │                                                    │
└──────────────────────────────────────────────────────────────┘
```

Sidebar should contain:

```text
Dashboard

Social Inbox
  ├── Messenger
  ├── Comments
  └── Conversations

Automation
  ├── Auto Reply
  ├── Auto Comment
  ├── Keyword Rules
  ├── AI Rules
  └── Spam Protection

AI Assistant
  ├── AI Chat
  ├── Business Knowledge
  ├── FAQ
  ├── Products & Services
  └── AI Settings

Leads
  ├── All Leads
  ├── New Leads
  ├── Hot Leads
  ├── Follow Up
  └── Lead Pipeline

Content
  ├── AI Content Generator
  ├── Posts
  ├── Media
  └── Scheduler

Analytics
  ├── Overview
  ├── Messages
  ├── Comments
  ├── Leads
  └── Conversion

Settings
  ├── Business Profile
  ├── Facebook
  ├── Instagram
  ├── AI Configuration
  ├── Notifications
  └── General Settings
```

---

# 3. TOP NAVBAR

Navbar should contain:

Left:

```text
☰
Page title
```

Right:

```text
Search
Notification Bell
AI Status
Admin Profile
```

AI status:

```text
● AI Online
```

Use a green status indicator.

---

# 4. DASHBOARD

Create a premium dashboard.

Top welcome section:

```text
Good Morning, Admin 👋

Here's what's happening with your social media today.
```

Add date.

## KPI Cards

Create 6 cards:

```text
Total Messages
1,284
+18.4%

Comments
846
+12.5%

AI Replies
1,052
+21.7%

New Leads
126
+15.2%

Hot Leads
38
+8.6%

Conversion Rate
12.8%
+3.4%
```

Cards should include:

* icon
* title
* value
* percentage change
* small trend indicator

---

# 5. DASHBOARD CHARTS

Use Chart.js.

Create:

### Messages Overview

Line chart:

```text
Messages
AI Replies
Human Replies
```

Filter:

```text
Today
7 Days
30 Days
90 Days
```

### Lead Overview

Bar chart:

```text
New
Contacted
Interested
Negotiation
Won
Lost
```

### AI Performance

Donut chart:

```text
AI Resolved
Human Handover
Unanswered
```

---

# 6. RECENT CONVERSATIONS

Create modern conversation card/table.

Columns:

```text
Customer
Platform
Last Message
AI Status
Lead Status
Time
Action
```

Example:

```text
Rahim Ahmed
Facebook
"Website করতে কত টাকা লাগবে?"
AI Replied
Hot Lead
2 min ago
View
```

---

# 7. SOCIAL INBOX

Create unified inbox interface.

Layout:

```text
┌───────────────┬───────────────────────────┬─────────────────┐
│ Conversations │ Conversation              │ Customer Info   │
│               │                           │                 │
│ Rahim         │ Rahim                     │ Name            │
│ Karim         │ Customer message          │ Phone           │
│ Sumaiya       │                           │ Lead Score      │
│               │ AI response               │ Service         │
│               │                           │                 │
│               │ Message box               │ Lead Status     │
└───────────────┴───────────────────────────┴─────────────────┘
```

Filters:

```text
All
Unread
AI Active
Human
Hot Leads
Facebook
Instagram
```

---

# 8. CONVERSATION PAGE

Show:

Customer profile

```text
Name
Profile Image
Facebook/Instagram
Phone
Email
Lead Score
Lead Status
Interested Service
```

Conversation:

```text
Customer message
↓
AI response
↓
Customer message
↓
AI response
```

AI messages should visually differ from human/admin messages.

At bottom:

```text
Type your message...

[AI Suggest Reply] [Send]
```

Add:

```text
Take Over Conversation
Return to AI
```

---

# 9. AUTO REPLY MODULE

Create complete Auto Reply management.

Page:

```text
Auto Reply
```

Top toggle:

```text
● Auto Reply Enabled
```

Create rules.

Fields:

```text
Rule Name
Platform
Trigger Type
Keywords
Response Type
Response
AI Enabled
Delay
Status
```

Trigger types:

```text
Any Message
Keyword
Exact Match
Contains
FAQ Match
Product Inquiry
Price Inquiry
Lead Inquiry
```

Example:

```text
Keyword:
price, দাম, কত, মূল্য

Response:
আপনার প্রয়োজন অনুযায়ী আমাদের service-এর pricing...
```

---

# 10. AUTO COMMENT MODULE

Create:

```text
Auto Comment
```

Features:

* Detect new comments
* Keyword matching
* AI classification
* AI-generated reply
* Predefined reply
* Spam detection
* Negative comment detection
* Hide spam where permitted
* Human review mode

Dashboard:

```text
Total Comments
Auto Replied
Pending Review
Spam
Negative
```

---

# 11. COMMENT DETAILS

Create comment list:

```text
Post
Customer
Comment
AI Classification
Reply Status
Lead Score
Action
```

AI classification:

```text
Sales Inquiry
General Question
Positive
Negative
Spam
Complaint
Price Inquiry
Product Inquiry
```

---

# 12. AI BUSINESS ASSISTANT

This is the core module.

Create:

```text
AI Assistant
```

Admin can configure:

```text
Business Name
Business Description
Business Category
Business Location
Phone
WhatsApp
Email
Website
Business Hours
Language
Tone
```

Tone:

```text
Professional
Friendly
Sales
Formal
Casual
```

Language:

```text
Bangla
English
Bangla + English
```

---

# 13. BUSINESS KNOWLEDGE

Create knowledge management.

Sections:

```text
Business Information
Services
Products
Pricing
Offers
Policies
FAQ
Contact Information
Custom Instructions
```

AI should use these records before generating responses.

Important:

The AI must NOT invent prices, offers, phone numbers, addresses or policies that do not exist in the database.

If information is unavailable:

```text
দুঃখিত, এই তথ্যটি নিশ্চিতভাবে জানাতে পারছি না। আমাদের team আপনাকে বিস্তারিত জানাতে পারবে।
```

---

# 14. PRODUCTS & SERVICES

Create CRUD.

Fields:

```text
Name
Category
Description
Features
Price
Discount Price
Availability
URL
Image
Status
```

Service example:

```text
Custom Website
Laravel Development
School Management Software
eCommerce Website
Real Estate Website
Travel Website
```

---

# 15. FAQ MODULE

Create FAQ CRUD.

Fields:

```text
Question
Answer
Category
Keywords
Priority
Status
```

AI should search FAQ before generating a completely new response.

---

# 16. AI CHAT

Create an internal AI chat interface.

UI:

```text
┌──────────────────────────────────────────────┐
│ AI Business Assistant                       │
├──────────────────────────────────────────────┤
│                                              │
│ User: Create a reply for website pricing    │
│                                              │
│ AI: আপনার website-এর প্রয়োজন অনুযায়ী...     │
│                                              │
├──────────────────────────────────────────────┤
│ Ask AI...                            [Send]   │
└──────────────────────────────────────────────┘
```

Buttons:

```text
Generate Reply
Rewrite
Shorten
Make Professional
Make Friendly
Translate
```

---

# 17. AI RESPONSE ENGINE

Create a centralized Laravel service:

```text
AIResponseService
```

Workflow:

```text
Incoming Message
        ↓
Identify Business
        ↓
Detect Language
        ↓
Detect Intent
        ↓
Search FAQ
        ↓
Search Products
        ↓
Search Services
        ↓
Search Business Knowledge
        ↓
Check Rules
        ↓
Generate AI Response
        ↓
Safety Validation
        ↓
Send Response
```

Intents:

```text
Greeting
Pricing
Product
Service
Availability
Location
Contact
Order
Complaint
Support
Lead
General
Spam
```

---

# 18. LEAD MANAGEMENT

Create CRM.

Lead table:

```text
ID
Name
Platform
Profile ID
Phone
Email
Interested Service
Lead Score
Lead Status
Source
Notes
Last Contact
Created At
```

Statuses:

```text
New
Contacted
Interested
Negotiation
Won
Lost
```

---

# 19. AI LEAD SCORING

AI should score leads:

```text
0-39
Cold

40-69
Potential

70-89
Warm

90-100
Hot
```

Example:

```text
Customer:
"আমি আজকেই website order করতে চাই। Price বলেন।"

AI:
Lead Score = 95
Intent = Purchase
Status = Hot
```

---

# 20. LEAD PIPELINE

Create Kanban board:

```text
NEW
│
├── Rahim
├── Karim

CONTACTED
│
├── Sumaiya

INTERESTED
│
├── Hasan

NEGOTIATION
│
├── Rakib

WON
│
└── Client A

LOST
```

Drag-and-drop status update.

---

# 21. HUMAN HANDOVER

AI must be able to hand over conversation.

Triggers:

```text
Customer requests human
Complaint
Complex question
AI confidence low
High-value lead
Admin manually takes over
```

Display:

```text
⚠ Human Assistance Required
```

Admin buttons:

```text
Take Over
Reply
Close
Return to AI
```

---

# 22. AI CONFIDENCE

Every AI answer should internally have a confidence level.

Example:

```text
Confidence: 94%
```

If confidence is low:

```text
< 60%
```

Do not automatically answer.

Move conversation to:

```text
Human Review
```

---

# 23. SPAM PROTECTION

Create spam detection.

Detect:

```text
Repeated comments
Promotional spam
Unrelated links
Scam messages
Off-topic messages
Abusive content
```

Actions:

```text
Ignore
Flag
Hide where supported
Human Review
```

Do not automatically delete/hide content unless the connected Meta API explicitly permits that action.

---

# 24. NEGATIVE / COMPLAINT DETECTION

Detect:

```text
Complaint
Angry Customer
Negative Feedback
Refund Request
Service Problem
```

Automatically create:

```text
Priority = High
Human Review = Yes
```

Suggested AI reply:

```text
আপনার সমস্যার জন্য আমরা দুঃখিত।
বিষয়টি দ্রুত সমাধানের জন্য আমাদের team আপনার সাথে যোগাযোগ করবে।
```

---

# 25. CONTENT GENERATOR

Create AI Content Generator.

Inputs:

```text
Topic
Platform
Business/Service
Language
Tone
Length
CTA
```

Output:

```text
Headline
Caption
CTA
Hashtags
```

Buttons:

```text
Generate
Regenerate
Shorten
Professional
Sales
Save
Schedule
```

---

# 26. POST SCHEDULER

Create:

```text
Scheduled Posts
```

Fields:

```text
Content
Image
Platform
Date
Time
Status
```

Status:

```text
Draft
Scheduled
Published
Failed
```

Use Laravel Scheduler + Queue.

---

# 27. MEDIA LIBRARY

Create media manager:

```text
Upload
Images
Videos
Documents
```

Features:

* Preview
* Search
* Delete
* Copy URL
* Use in post

---

# 28. FACEBOOK INTEGRATION

Create Meta integration settings.

Fields:

```text
App ID
App Secret
Access Token
Page ID
```

Use environment variables where appropriate.

Example:

```text
META_APP_ID=
META_APP_SECRET=
META_REDIRECT_URI=
META_VERIFY_TOKEN=
```

Never expose secrets in frontend.

---

# 29. FACEBOOK WEBHOOK

Implement webhook endpoints for supported events.

Example:

```text
GET /webhooks/facebook
POST /webhooks/facebook
```

GET:

Verify webhook.

POST:

Receive:

```text
Comment
Message
Post Event
```

Store incoming events in database.

Use queues for processing.

Do not process heavy AI tasks directly inside webhook request.

---

# 30. QUEUE ARCHITECTURE

Use Laravel Queue.

Jobs:

```text
ProcessIncomingMessage
ProcessIncomingComment
GenerateAIResponse
SendFacebookReply
SendInstagramReply
DetectLead
CalculateLeadScore
GenerateContent
PublishScheduledPost
SendNotification
```

Flow:

```text
Webhook
 ↓
Store Event
 ↓
Dispatch Job
 ↓
Queue
 ↓
AI Processing
 ↓
Response
 ↓
API
```

---

# 31. DATABASE TABLES

Create proper migrations.

Required tables:

```text
users

business_settings

social_accounts

facebook_pages

instagram_accounts

webhook_events

conversations

conversation_messages

comments

automation_rules

auto_reply_rules

auto_comment_rules

business_knowledge

services

products

faqs

ai_conversations

ai_messages

leads

lead_activities

lead_notes

content_posts

scheduled_posts

media

notifications

activity_logs

system_settings
```

Use proper foreign keys, indexes and timestamps.

---

# 32. IMPORTANT DATABASE RELATIONSHIPS

Example:

```text
Business
 ├── Social Accounts
 ├── Conversations
 ├── Comments
 ├── Leads
 ├── Services
 ├── Products
 ├── FAQs
 ├── Knowledge
 └── Posts
```

Conversation:

```text
Conversation
 └── Messages
```

Lead:

```text
Lead
 └── Lead Activities
```

---

# 33. SECURITY

Implement:

* CSRF protection
* Authentication
* Authorization
* Form Request Validation
* Rate Limiting
* API token protection
* Encrypted sensitive credentials
* Secure webhook verification
* XSS protection
* SQL injection protection
* Permission checks
* Activity logs

Never expose:

```text
APP_KEY
API Secret
Meta App Secret
Access Token
AI API Key
```

---

# 34. SETTINGS

Create:

```text
General Settings
Business Settings
AI Settings
Facebook Settings
Instagram Settings
Notification Settings
Automation Settings
Security Settings
```

AI Settings:

```text
AI Provider
API Key
Model
Temperature
Max Tokens
Default Language
Default Tone
Confidence Threshold
```

---

# 35. NOTIFICATIONS

Admin notifications:

```text
🔥 Hot Lead
⚠ Human Review Required
💬 New Message
📩 New Comment
❌ Automation Failed
⚠ API Connection Error
```

Create notification center in navbar.

---

# 36. ACTIVITY LOG

Record:

```text
Admin Login
AI Reply
Manual Reply
Lead Created
Lead Updated
Rule Created
Rule Updated
Facebook Connected
API Error
Settings Updated
```

---

# 37. ERROR HANDLING

Create friendly error pages:

```text
404
403
419
429
500
503
```

API errors should be logged.

AI failures should not crash the application.

If AI API fails:

```text
AI unavailable.
Move conversation to human review.
```

---

# 38. RESPONSIVE DESIGN

Desktop:

```text
Sidebar + Content
```

Tablet:

```text
Collapsible Sidebar
```

Mobile:

```text
Top Navbar
Offcanvas Sidebar
Full-width Content
```

Inbox should be mobile friendly.

Cards should stack properly.

Tables should become responsive.

---

# 39. DASHBOARD COLOR / VISUAL STYLE

Use a professional palette based around:

```text
White
Light Gray
Dark Navy
Black
Blue
Purple
Green for success
Orange for warning
Red for errors
```

Do not make every section colorful.

Keep the design minimal and premium.

Use subtle shadows and rounded corners.

---

# 40. AI VISUAL LANGUAGE

AI-related elements can use:

```text
AI icon
Sparkle icon
Robot icon
Magic wand
Brain icon
```

But use icons minimally.

Example:

```text
✨ AI Generated
● AI Active
🤖 AI Assistant
```

---

# 41. SAMPLE BUSINESS

For testing, create seed data for:

```text
Business Name:
H Tech Provision IT

Category:
IT & Software

Services:
Custom Website
eCommerce Website
School Management Software
Pharmacy Management Software
Real Estate Website
Travel Website
News Portal
Custom Laravel Software

Language:
Bangla + English

Tone:
Professional + Friendly
```

Create sample FAQs and sample products/services.

---

# 42. BANGLA SUPPORT

The interface can be English by default, but AI replies must support:

```text
Bangla
English
Bangla + English
```

AI should detect customer's language automatically.

Example:

Customer:

```text
দাম কত?
```

Reply in Bangla.

Customer:

```text
How much is the website?
```

Reply in English.

Customer:

```text
website er price koto?
```

Reply in natural Bangla.

---

# 43. AI PROMPT ARCHITECTURE

Create a centralized system prompt.

AI must follow:

```text
You are the official AI business assistant.

You represent the business.

Only use verified business information.

Never invent pricing, availability, policies, contact information or offers.

If information is unavailable, clearly say that a human team member will provide the information.

Be concise.

Be professional.

Do not argue with customers.

Do not make false promises.

Detect customer intent.

Identify potential leads.

Escalate complaints and uncertain questions to humans.
```

Inject business data dynamically.

---

# 44. ADMIN APPROVAL MODE

Add automation mode:

```text
Full Automatic
Semi Automatic
Manual Approval
```

### Full Automatic

AI automatically sends approved responses.

### Semi Automatic

AI generates response → Admin approves → Send.

### Manual Approval

AI only suggests responses.

---

# 45. AUTOMATION DELAY

Allow:

```text
Immediate
5 seconds
10 seconds
30 seconds
1 minute
Custom
```

Use queue jobs.

---

# 46. DUPLICATE REPLY PROTECTION

The system must not reply twice to the same comment/message.

Store:

```text
external_message_id
external_comment_id
```

Add unique indexes.

Before sending:

```text
Already processed?
YES → Stop
NO → Continue
```

---

# 47. API ARCHITECTURE

Create clean services:

```text
MetaService
FacebookService
InstagramService
AIService
LeadService
AutomationService
ConversationService
NotificationService
```

Do not put everything inside controllers.

Controllers should remain thin.

---

# 48. CODE STRUCTURE

Use:

```text
app/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Middleware/
│
├── Models/
│
├── Services/
│   ├── AI/
│   ├── Meta/
│   ├── Lead/
│   ├── Automation/
│   └── Social/
│
├── Jobs/
├── Events/
├── Listeners/
├── Notifications/
└── Policies/
```

Blade:

```text
resources/views/
├── layouts/
├── components/
├── dashboard/
├── inbox/
├── automation/
├── ai/
├── leads/
├── content/
├── analytics/
└── settings/
```

---

# 49. IMPORTANT DEVELOPMENT RULE

Do NOT create fake functionality.

If Meta API credentials are missing:

Show:

```text
Facebook is not connected.
Connect your Facebook Page to enable automation.
```

Do not pretend that the message was actually sent.

If AI API is not configured:

Show:

```text
AI service is not configured.
Please configure the AI API from Settings.
```

---

# 50. FINAL EXPECTATION

The final application should feel like a real professional product.

It should have:

```text
Professional Login
        ↓
Dashboard
        ↓
Social Inbox
        ↓
Automation
        ↓
AI Assistant
        ↓
Business Knowledge
        ↓
Lead Management
        ↓
Content
        ↓
Analytics
        ↓
Settings
```

The UI must be fully functional, not just static HTML.

All CRUD pages should work.

All forms should validate.

All database migrations should work.

All relationships should work.

All dashboard statistics should use real database data.

Use Laravel best practices.

Use reusable Blade components.

Use AJAX/Fetch where appropriate.

Use queues for AI and social-media processing.

Use Laravel Scheduler for scheduled tasks.

Use service classes for external APIs.

Use proper logging and exception handling.

Create seeders and demo data.

Create `.env.example`.

Create installation instructions.

Create database migrations.

Create models.

Create controllers.

Create services.

Create jobs.

Create webhook handlers.

Create Blade views.

Create CSS/JS.

Create routes.

Create API routes where required.

The project should be ready for local development and later deployment to a production Linux/cPanel/VPS environment.

Do not skip backend functionality.

Do not create only a frontend mockup.

Build the project feature-by-feature and keep the code maintainable.
