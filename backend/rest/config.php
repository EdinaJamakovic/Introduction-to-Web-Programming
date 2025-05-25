<?php


// Set the reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));


class Config
{
   public static function DB_NAME()
   {
       return 'dental_clinic'; 
   }
   public static function DB_PORT()
   {
       return  3307;

    }
   public static function DB_USER()
   {
       return 'root';
   }
   public static function DB_PASSWORD()
   {
       return '27012006';
   }
   public static function DB_HOST()
   {
       return 'localhost:3307';
   }


   public static function JWT_SECRET(){
    return "7{K7ee$!}+ZU2/0A0\$t7N}%W?Y5{:..-F(eJ]6#KaHz.1K8xg:=)}dD5.;M-nh(8ek5Bk&0)*i.uW?T#.4Gw}@%8ShVGKirpzVmAy[EvfV=2TeNDu)pQQx;z+9}/S/Cr=Q*A+p#RcWf_QLX,,+5]-;Rq](MpPN[f{5-\$C*5&q.R?(\$k{.H)y}wh*(@EUeHqBq,dWutf0*VwP,H5k,t/vN!8MHe#1Wz+Z_DnB%*.!1FS7hH=Jbv@pZ{RS6@dKNdB4q9fM2LQiryS,ykHWw$\$q8.Rnv(JJf0Aurw/[,5?:fBPP";
    }
}


class Database {
   private static $connection = null;


   public static function connect() {
       if (self::$connection === null) {
           try {
               self::$connection = new PDO(
                   "mysql:host=" . Config::DB_HOST() . ";dbname=" . Config::DB_NAME(),
                   Config::DB_USER(),
                   Config::DB_PASSWORD(),
                   [
                       PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                       PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                   ]
               );
           } catch (PDOException $e) {
               die("Connection failed: " . $e->getMessage());
           }
       }
       return self::$connection;
   }
}
