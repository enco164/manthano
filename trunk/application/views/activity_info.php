{{message}}
Path:
<a ng-repeat="location in activity.path" href="#/activity/{{location.idActivity}}"> {{location.Name}} / </a>
<br/>
Ime: {{activity.name}}
<br/>
Opis: {{activity.description}}
<br/>
Pocetak: {{activity.date}}
<br/>
Cover: {{activity.cover}}
<br/>
<a  href="#/activity/new/{{activity.id}}"> Dodaj kurs.  </a>
<br/>
<a href="#/activity/modify/{{activity.id}}"> Promeni kurs. </a>
<br/>
<button ng-click="deleteActivity()"> Obrisi kurs. </button>
<br/>
Sinovi:

{{danijel}}

<ul>
    <li ng-repeat="son in activity.sons"> <a  href="#/activity/{{son.idActivity}}"> {{son.Name}}  </a> </li>
</ul>
