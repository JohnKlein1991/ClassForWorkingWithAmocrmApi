# ClassForWorkingWithAmocrmApi
 В файле main.php содержится класс WorkWithApi для работы с api amocrm. При создании нового обьекта ,конструктор принимаает три параметра: логин, хэш пароля (можно узнать в профиле) и поддосен.
 
 Методы: authorization() - производит авторизацию.
         searchLeadsWithoutTasks() - ищет в профиле, по которому Вы авторизировались , сделки без задач и записывает id таких сделок в свойство arrEmptyLeads в виде массива
         addNewTasksInEmptyLeads() - во все сделки, id которых записаны в свойстве arrEmptyLeads, добавляет задачу с текстом "Сделка без задачи" и сроком выполнения через час после добавления
         
