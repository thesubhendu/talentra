# **Talentra ATS – MVP Roadmap**


## **Core Goal**

Build a _small, polished, AI-first ATS workflow_ that demonstrates your skills in Laravel, AI, vector search, and clean architecture with a focus on performance and scalability.

## **MVP Scope (Only These 4 Features)**

### **1. Job Management (Admin Only)**

- Create job
- Edit job
- List jobs
- Fields: title, description, required skills, experience level
**
### **2. Candidate Application**

- Simple application form
- Upload PDF resume
- Store candidate record
- Queue job: extract + clean resume text
---
### **3. AI Screening & Match Score**

- Generate embeddings (resume + job)
    
- Save vectors in pgvector
    
- Compute similarity score
    
- Display match % visually
    

---

### **4. AI Screening Report**

Generate 4 sections using OpenAI:

- Summary of experience
    
- Strengths
    
- Weaknesses
    
- Suggested interview questions


---
## **Architecture Constraints**

Stick to this structure:
```
app/
  Actions/
  Services/
  DTO/
  Jobs/
  Models/
  Http/
    Controllers/
```
## **AI Pipeline (Strictly This)**

1. Extract resume text
    
2. Create candidate embedding
    
3. Create job embedding
    
4. Compute similarity
    
5. Generate AI report

**No chatbots. No extra tools. No agent memory.**
## **AI Pipeline (Strictly This)**

1. Extract resume text
    
2. Create candidate embedding
    
3. Create job embedding
    
4. Compute similarity
    
5. Generate AI report
    

  

**No chatbots. No extra tools. No agent memory.**
## **🚀** 

## **Success Criteria**

Your MVP is _done_ when you can:

1. Create a job
    
2. Upload a resume
    
3. See match score
    
4. View an AI-generated report
    
5. Run everything end-to-end without breaking
## **⏳** 

## **Suggested Build Timeline**

- Day 1–2 → Project setup + DB + models
    
- Day 3–4 → Job create/list
    
- Day 5–7 → Resume upload + text extraction
    
- Day 8–10 → Embeddings + similarity
    
- Day 11–13 → AI report
    
- Day 14 → Clean UI + README + screenshots