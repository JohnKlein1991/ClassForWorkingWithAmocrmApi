<?php

class WorkWithApi {
    protected $login;
    protected $hash;
    protected $subdomen;
    protected $arrEmptyLeads = [];
    public function __construct($login, $hash, $subdomen) {
        $this->login = $login;
        $this->hash = $hash;
        $this->subdomen = $subdomen;
    }
    public function authorization() {
        $ch = curl_init();
        $data = [
            'USER_LOGIN' => $this->login,
            'USER_HASH' => $this->hash
        ];
        $link = 'https://'.$this->subdomen.'.amocrm.ru/private/api/auth.php';
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt');
        curl_setopt($ch,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_exec($ch);
        curl_close($ch);
    }
    public function searchLeadsWithoutTasks() {
        $link = 'https://'.$this->subdomen.'.amocrm.ru/api/v2/leads';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);        
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt');
        curl_setopt($ch,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt');
        $response = curl_exec($ch);
        $response = json_decode($response,true);
        $leads = $response['_embedded']['items'];
        foreach ($leads as $key => $value) {
            if ($value['closest_task_at'] == 0){
                $this->arrEmptyLeads[] = $value['id'];
            }
        }
        curl_close($ch);
    }
    public function addNewTasksInEmptyLeads() {
        foreach ($this->arrEmptyLeads as $value) {
            $data = array (
            'add' => 
            array (
              0 => 
              array (
                'element_id' => $value,
                'element_type' => '2',
                'complete_till' => time() + 3600,
                'task_type' => '1',
                'text' => 'Сделка без задачи',
              ),
            ),
          );
          $link = "https://buk2018irinam.amocrm.ru/api/v2/tasks";


          $curl = curl_init();
          curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
          curl_setopt($curl, CURLOPT_URL, $link);
          curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__)."/cookie.txt");
          curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__)."/cookie.txt");
          $out = curl_exec($curl);
          curl_close($curl);
          $this->arrEmptyLeads = [];
        }
    }
}

$myApi = new WorkWithApi('test@test.com','xxxxxxxxxxxxxxxxxxxxxxxxxxx','test');
$myApi->authorization();
$myApi->searchLeadsWithoutTasks();
$myApi->addNewTasksInEmptyLeads();
