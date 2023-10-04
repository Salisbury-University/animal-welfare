### Do not merge. 
### Note: The database will be changing to mark the saved section averages as text as follows:

welfaresubmission : 

    wid, zim, dates, reason, avg_health, avg_nutrition, avg_behavior, avg_mental, responses, fid

    =>  wid, zim, dates, reason, averages, responses, fid
                
Where the averages is a text file with format: 

    "'Total Average', 'Section One Average', 'Section Two Average', ..., 'Section X Average'"
  
For example, the following demonstrates entries for form with 6 and 5 sections respectively:

    6 =  "3,1,2,3,3,4,5"

    5 =  "3,1,2,3,4,5"

### Reason: 

  With the ability to add and remove sections; not all forms may have the same sections for organizational purposes - allows forms to constantly change without changing structure and no need to update the query code 
  
