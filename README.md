This API developed and tested on PHP v 7.0+ Laravel 8.0+

<ol>to run the app:
    <li>clone this project by command or download zip
    <li>run command "composer install"
    <li>run command "npm install"
    <li>connect your database
    <li>run command "php artisan migrate"
    <li>run command "php artisan db:seed"
    <li>Enjoy :)
</ol>

<p>Query examples:
<br>Create, Update: 
<br>
{
    "parent_id": 1,
    "status": 0,
    "priority": 2,
    "title": "",
    "user_id": 2,
    "description": "test task",
    "completion_time": ""
}
<br>
Update Status:
<br>
{
    "status": "todo or done"
}
<br>
Search with filters:
<br>
{
    "status": "done",
    "priority": {
        "0": "0", 
        "1": "2"
    },
    "sorted": "sort_method"
}
<br>
or search only by title:
<br>
{
    "title": "",
    "sorted": "sort_method"
}
<br>
Delete the task:
<br>
You need to provide "id" to the url.
