<?php
class DataBase{
    const RETURN_SUCCESSFUL=0;
    const RETURN_AFFECTED=1;
    const RETURN_INSERT_ID=2;
    /**
     *
     * @var PDO connection to db
     */
    private $_link;
    
    public $table_perfix='';
    /**
     * @param string $dsn dsn of database for use in pdo
     * @param string $user user of database
     * @param string $pass password of user
     * @param string $perfix perfix string to automatic added in table name
     */
    function __construct($dsn,$user,$pass,$perfix='') {
        $this->_link=new PDO($dsn,$user,$pass);
        $this->_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->_link->exec("set names utf8");
        $this->table_perfix=$perfix;
    }
    /**
     * return name of table by added perfix element
     * @param string $name
     * @return string
     */
    function get_table_name($name) {
        return $this->table_perfix.$name;
    }
    /**
     * execute sql command and return value
     *
     * if returnmode is<br/>
     * 1- RETURN_SUCCESSFUL if sql command run successful return true,or return false<br/>
     * 2- RETURN_AFFECTED return effected element by sql command <br/>
     * 3- RETURN_INSERT_ID return inserted item id <br/>
     *
     * @param string $sql sql command
     * @param array $params parameter to set in prepared sql command,is associative array
     * @param number $returnmode use self::RETURN_SUCCESSFUL,self::RETURN_AFFECTED,RETURN_INSERT_ID
     * @return boolean|number return value depend on return mode parameter
     */
    function execute($sql,$params=[],$returnmode=self::RETURN_SUCCESSFUL){
        try{
            $stmt =$this->_link->prepare($sql);
            foreach ($params as $key => $value){
                $stmt->bindValue(':'.$key, $value);
            }
            $stmt->execute();
            if($returnmode==self::RETURN_SUCCESSFUL){
                return true;
            }elseif ($returnmode==self::RETURN_AFFECTED){
                return $stmt->rowCount();
            }else{
                return $this->_link->lastInsertId();
            }
        }catch (Exception $e){
            if($returnmode==self::RETURN_SUCCESSFUL){
                return false;
            }else{
                return 0;
            }
        }
    }
    /**
     * execute sql query and return value array of associative array from data
     * @param string $sql sql query
     * @param array $params parameter to set in prepared sql command,is associative array
     * @return array
     */
    function query($sql,$params=[]){
        
        try{
            $stmt =$this->_link->prepare($sql);
            foreach ($params as $key => $value){
                $stmt->bindValue(':'.$key, $value);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (Exception $e){
            return [];
        }
    }
    /**
     * run insert command
     * @param string $table table name ,automatic added table perfix for table name
     * @param array $data data is associative array of row data ,associative array key is field name and value is value for this record
     * @return number inserted id
     */
    function insert($table, $data) {
        $sql="INSERT INTO " .  $this->table_perfix . $table .' ';
        $varname=[];
        $varvalue=[];
        
        foreach (array_keys($data) as  $key ) {
            $varname[] = '`' . $key . '`';
            $varvalue[] = ' :'.$key.' ';
        }
        $sql.='('. implode ( ', ', $varname) .' ) VALUES ( '.implode ( ', ', $varvalue).')';
        return $this->execute($sql,$data,self::RETURN_INSERT_ID);
    }
    /**
     * update element by id
     * @param string $table  table name
     * @param array $data associative array of data  for replace in update command
     * @param number $id element id for update data
     * @return bool if update element,return true
     */
    function update_id($table, $data, $id ) {
        $params=[];
        $vars=[];
        $params['id']=$id;
        foreach ( $data as $key => $val ) {
            $vars[] = '`' . $key . '`' . " = " . ' :_'.$key.' ';
            $params['_'.$key]=$val;
        }
        $sql = 'UPDATE ' . $this->table_perfix .$table  . ' SET ' . implode ( ', ', $vars ) .' WHERE `id` =  :id ';
        return $this->execute($sql,$params,self::RETURN_AFFECTED)==1 ?true : false;
    }
    /**
     * update element by where and prepared parameter
     * @param string $table  table name
     * @param array $data associative array of data  for replace in update command
     * @param string $where  string use where ,string maybe content prepared sql command format
     * @param array $param prepared sql command parameters
     * @return number effected element by this command
     */
    function update($table, $data, $where = '',$param=array()) {
        $params=[];
        foreach ( $param as $key => $val ) {
            $params[$key]=$val;
        }
        $vars=[];
        foreach ( $data as $key => $val ) {
            $vars[] = '`' . $key . '`' . " = " . ' :_'.$key.' ';
            $params['_'.$key]=$val;
        }
        $sql = 'UPDATE ' . $this->table_perfix .$table  . ' SET ' . implode ( ', ', $vars );
        $sql .= ($where != '') ? ' WHERE ' . $where : '';
        
        return $this->execute($sql,$params,self::RETURN_AFFECTED);
    }
    /**
     * update element by parameter
     * @param string $table  table name
     * @param array $data data  for replace in update command
     * @param array $param is associative arrray ,make where by array item like sample <br/> [a=>b,c=>d,e=>f]  this equal "a=b and c=d and e=f"
     * @return number effected element by this command
     */
    function update_parameter($table, $data,$param=array()) {
        $params=[];
        $wheres = [];
        foreach ( $param as $key => $val ) {
            $wheres[]= '`' . $key . '`' . " = " . ' :'.$key.' ';
            $params[$key]=$val;
        }
        $where=implode(' AND ',$wheres);
        $vars=[];
        foreach ( $data as $key => $val ) {
            $vars[] = '`' . $key . '`' . " = " . ' :_'.$key.' ';
            $params['_'.$key]=$val;
        }
        $sql = 'UPDATE ' . $this->table_perfix .$table  . ' SET ' . implode ( ', ', $vars );
        $sql .= ($where!= '') ? ' WHERE ' . $where : '';
        
        return $this->execute($sql,$params,self::RETURN_AFFECTED);
    }
    /**
     * run delete command
     * @param string $table  table name
     * @param number $id element id for delete 
     * @return bool if delete element,return true
     */
    function delete_id($table, $id = '') {
        $sql = "DELETE FROM " .  $this->table_perfix .$table  .' WHERE `id` =  :id ';
        return $this->execute($sql,['id'=>$id],self::RETURN_AFFECTED)==1?true:false;
    }
    /**
     * delete element by where and prepared parameter
     * @param string $table  table name
     * @param string $where  string use where ,string maybe content prepared sql command format
     * @param array $param prepared sql command parameters
     * @return number effected element by this command
     */
    function delete($table, $where = '',$param=[], $limit = -1) {
        $params=[];
        foreach ( $param as $key => $val ) {
            $params[$key]=$val;
        }
        $lim = ( $limit==-1) ? '' : ' LIMIT ' . $limit;
        $sql = "DELETE FROM " .  $this->table_perfix .$table  ;
        $sql .= ($where != '') ? ' WHERE ' . $where : '';
        $sql .=' '.$lim;
        return $this->execute($sql,$params,self::RETURN_AFFECTED);
    }
    /**
     * delete element by parameter
     * @param string $table  table name
     * @param array $param is associative arrray ,make where by array item like sample <br/> [a=>b,c=>d,e=>f]  this equal "a=b and c=d and e=f"
     * @return number effected element by this command
     */
    function delete_parameter($table, $where = '',$param=[], $limit = -1) {
        $params=[];
        $wheres = [];
        foreach ( $param as $key => $val ) {
            $wheres[]= '`' . $key . '`' . " = " . ' :'.$key.' ';
            $params[$key]=$val;
        }
        $where=implode(' AND ',$wheres);
        $lim = ( $limit==-1) ? '' : ' LIMIT ' . $limit;
        $sql = "DELETE FROM " .  $this->table_perfix .$table  ;
        $sql .= ($where!= '') ? ' WHERE ' . $where : '';
        $sql .=' '.$lim;
        return $this->execute($sql,$params,self::RETURN_AFFECTED);
    }
    /**
     * return select query in array format,by where and prepared parameter 
     * @param string $table table name
     * @param string $where  string use where ,string maybe content prepared sql command format
     * @param array $param prepared sql command parameters
     * @param number $offset offset of limit command
     * @param number $limit count of limit command
     * @param string $by fielname for sort ,can list of name in array or name seprate by ',' in string
     * @param string $order type of sort ASC or DESC
     * @return array of elements ,array of associative array
     */
    function select($table,$where='',$param=[], $offset = 0, $limit = 0, $by = '', $order = 'ASC'){
        $params=[];
        foreach ( $param as $key => $val ) {
            $params[$key]=$val;
        }
        $sql = 'SELECT * FROM '.$this->table_perfix .$table;
        $sql .= ($where != '') ? ' WHERE ' . $where : '';
        
        if(!is_array($by)){
            if(is_string($by)){
                if($by != ''){
                    $by=explode ( ',', $by );
                }else{
                    $by=[];
                }
            }else{
                $by=[];
            }
        }
        
        if (count($by)>0) {
            $bya=[];
            for($i = 0; $i < count ( $by ); $i ++) {
                $bya [] = ' `' . $by [$i] . '` ' . $order . ' ';
            }
            $sql .=  ' ORDER BY '.implode ( ',', $bya );;
        }
        
        if ($limit != 0){
            $sql .= ' LIMIT ' . $offset . ' , ' . $limit;
        }
        return $this->query($sql,$params);
    }
    /**
     * return select query in array format,by parameter
     * @param string $table table name
     * @param array $param associative arrray ,make where by array item like sample <br/> [a=>b,c=>d,e=>f]  this equal "a=b and c=d and e=f"
     * @param number $offset offset of limit command
     * @param number $limit count of limit command
     * @param string $by fielname for sort ,can list of name in array or name seprate by ',' in string
     * @param string $order type of sort ASC or DESC
     * @return array of elements ,array of associative array
     */
    function select_parameter($table,$param=[], $offset = 0, $limit = 0, $by = '', $order = 'ASC'){
        $params=[];
        $wheres = [];
        foreach ( $param as $key => $val ) {
            $wheres[]= '`' . $key . '`' . " = " . ' :'.$key.' ';
            $params[$key]=$val;
        }
        $where=implode(' AND ',$wheres);
        $sql = 'SELECT * FROM '.$this->table_perfix .$table;
        $sql .= ($where != '') ? ' WHERE ' . $where : '';
        
        if(!is_array($by)){
            if(is_string($by)){
                if($by != ''){
                    $by=explode ( ',', $by );
                }else{
                    $by=[];
                }
            }else{
                $by=[];
            }
        }
        
        if (count($by)>0) {
            $bya=[];
            for($i = 0; $i < count ( $by ); $i ++) {
                $bya [] = ' `' . $by [$i] . '` ' . $order . ' ';
            }
            $sql .=  ' ORDER BY '.implode ( ',', $bya );;
        }
        
        if ($limit != 0){
            $sql .= ' LIMIT ' . $offset . ' , ' . $limit;
        }
        return $this->query($sql,$params);
    }
    /**
     * return element by id
     * @return NULL|array element by associative array format
     */
    function get_id($table,$id=''){
        $sql = "SELECT * FROM " .  $this->table_perfix .$table  .' WHERE `id` =  :id ';
        $items=$this->query($sql,['id'=>$id]);
        if(count($items)>0){
            return $items[0];
        }
        return null;
    }
    /**
     * like select command but return first element,by where and prepared parameter
     * @param string $table table name
     * @param string $where  string use where ,string maybe content prepared sql command format
     * @param array $param prepared sql command parameters
     * @param string $by fielname for sort ,can list of name in array or name seprate by ',' in string
     * @param string $order type of sort ASC or DESC
     * @return NULL|array
     */
    function get($table,$where='',$params=[], $by = '', $order = 'ASC'){
        $a=$this->select($table,$where,$params,0,1,$by,$order);
        if(count($a)>0){
            return $a[0];
        }
        return null;
    }
    /**
     * like select command but return first element ,by parameter
     * @param string $table table name
     * @param array $param associative arrray ,make where by array item like sample <br/> [a=>b,c=>d,e=>f]  this equal "a=b and c=d and e=f"
     * @param string $by fielname for sort ,can list of name in array or name seprate by ',' in string
     * @param string $order type of sort ASC or DESC
     * @return NULL|array
     */
    function get_parameter($table,$params=[], $by = '', $order = 'ASC'){
        $a=$this->select_parameter($table,$params,0,1,$by,$order);
        if(count($a)>0){
            return $a[0];
        }
        return null;
    }
    /**
     * like select only return count of element,by where and prepared parameter
     * @param string $table table name
     * @param string $where  string use where ,string maybe content prepared sql command format
     * @param array $param prepared sql command parameters
     * @param number $offset offset of limit command
     * @param number $limit count of limit command
     * @param string $by fielname for sort ,can list of name in array or name seprate by ',' in string
     * @param string $order type of sort ASC or DESC
     * @return number
     */
    function count($table, $where='',$param=[]) {
        $sql = 'SELECT count(*) FROM '.$this->table_perfix .$table;
        $sql .= ($where != '') ? ' WHERE ' . $where : '';
        try{
            $stmt =$this->_link->prepare($sql);
            foreach ($param as $key => $value){
                $stmt->bindValue(':'.$key, $value);
            }
            $stmt->execute();
            return $stmt->fetchColumn();
        }catch (Exception $e){
            return 0;
        }
    }
    /**
     * like select only return count of element,by parameter
     * @param string $table table name
     * @param array $param associative arrray ,make where by array item like sample <br/> [a=>b,c=>d,e=>f]  this equal "a=b and c=d and e=f"
     * @return number
     */
    function count_parameter($table,$param=[]) {
        $params=[];
        $wheres = [];
        foreach ( $param as $key => $val ) {
            $wheres[]= '`' . $key . '`' . " = " . ' :'.$key.' ';
            $params[$key]=$val;
        }
        $where=implode(' AND ',$wheres);
        
        $sql = 'SELECT count(*) FROM '.$this->table_perfix .$table;
        $sql .= ($where != '') ? ' WHERE ' . $where : '';
        try{
            $stmt =$this->_link->prepare($sql);
            foreach ($params as $key => $value){
                $stmt->bindValue(':'.$key, $value);
            }
            $stmt->execute();
            return $stmt->fetchColumn();
        }catch (Exception $e){
            return 0;
        }
    }
    /**
     * backup from database in sql file
     * @param string $tables table names ,seprate by ',' or * for all table
     * @param string $fileName filename without extension
     * @param boolean $compression use gzip for compression
     * @return string filename
     */
    function backup($tables = '*', $fileName = '',$compression=true) {
        // only modify http://www.matteomattei.com/how-to-backup-mysql-data-and-schema-in-php/
        $lastMode=$this->_link->getAttribute(PDO::ATTR_ORACLE_NULLS);
        $this->_link->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_NATURAL );
        if($fileName=='')
            $fileName = '/backup-' . date('d-m-Y');
            
            if ($compression){
                $fileName .= '.sql.gz';
                $zp = gzopen(BASEPATH.$fileName, "a9");
            }else{
                $fileName .= '.sql';
                $handle = fopen(BASEPATH.$fileName,'a+');
            }
            //array of all database field types which just take numbers
            $numtypes=['tinyint','smallint','mediumint','int','bigint','float','double','decimal','real'];
            //get all of the tables
            if($tables=='*'){
                $tables=[];
                $pstm1 = $this->_link->query('SHOW TABLES');
                while ($row = $pstm1->fetch(PDO::FETCH_NUM)){
                    $tables[] = $row[0];
                }
            }else{
                $tables = is_array($tables) ? $tables : explode(',',$tables);
            }
            //cycle through the table(s)
            foreach($tables as $table){
                $result = $this->_link->query('SELECT * FROM '.$table);
                $num_fields = $result->columnCount();
                $num_rows = $result->rowCount();
                $return="";
                //uncomment below if you want 'DROP TABLE IF EXISTS' displayed
                //$return.= 'DROP TABLE IF EXISTS `'.$table.'`;';
                //table structure
                $pstm2 = $this->_link->query('SHOW CREATE TABLE '.$table);
                $row2 = $pstm2->fetch(PDO::FETCH_NUM);
                $ifnotexists = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $row2[1]);
                $return.= "\n\n".$ifnotexists.";\n\n";
                if ($compression){
                    gzwrite($zp, $return);
                }else{
                    fwrite($handle,$return);
                }
                $return = "";
                //insert values
                if ($num_rows)
                {
                    $return= 'INSERT INTO `'.$table.'` (';
                    $pstm3 = $this->_link->query('SHOW COLUMNS FROM '.$table);
                    $count = 0;
                    $type = [];
                    while ($rows = $pstm3->fetch(PDO::FETCH_NUM)){
                        if (stripos($rows[1], '(')){
                            $type[$table][] = stristr($rows[1], '(', true);
                        }else{
                            $type[$table][] = $rows[1];
                        }
                        $return.= '`'.$rows[0].'`';
                        $count++;
                        if ($count < ($pstm3->rowCount())){
                            $return.= ", ";
                        }
                    }
                    $return.= ')'.' VALUES';
                    if ($compression){
                        gzwrite($zp, $return);
                    }else{
                        fwrite($handle,$return);
                    }
                    $return = "";
                }
                $count =0;
                while($row = $result->fetch(PDO::FETCH_NUM)){
                    $return= "\n(";
                    for($j=0; $j<$num_fields; $j++){
                        if (isset($row[$j])){
                            //if number, take away "". else leave as string
                            if ((in_array($type[$table][$j], $numtypes)) && $row[$j]!==''){
                                $return.= $row[$j];
                            }else{
                                $return.= $this->_link->quote($row[$j]);
                            }
                        }else{
                            $return.= 'NULL';
                        }
                        if ($j<($num_fields-1)){
                            $return.= ',';
                        }
                    }
                    $count++;
                    if ($count < ($result->rowCount())){
                        $return.= "),";
                    }else{
                        $return.= ");";
                    }
                    if ($compression){
                        gzwrite($zp, $return);
                    }else{
                        fwrite($handle,$return);
                    }
                    $return = "";
                }
                $return="\n\n-- ------------------------------------------------ \n\n";
                if ($compression){
                    gzwrite($zp, $return);
                }else{
                    fwrite($handle,$return);
                }
                $return = "";
            }
            if ($compression){
                gzclose($zp);
            }else{
                fclose($handle);
            }
            
            $this->_link->setAttribute(PDO::ATTR_ORACLE_NULLS,$lastMode);
            return $fileName;
    }
    
}

?>
