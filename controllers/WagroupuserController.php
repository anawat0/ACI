<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\web\Utils;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\helpers\Url;
use yii\helpers\BaseArrayHelper;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\WA_GROUP;
use app\models\WA_GROUP_USER;
use app\models\WA_USER;

class WagroupuserController extends AppController 
{
	public $title = 'WA002 จัดการผู้ใช้งาน ของกลุ่มผู้ใช้งาน';
	private $tempPassword = '******';

	public function behaviors() {
		return [ 
				'access' => [ 
						'class' => AccessControl::className (),
						'only' => [ 
								'logout' 
						],
						'rules' => [ 
								[ 
										'actions' => [ 
												'logout' 
										],
										'allow' => true,
										'roles' => [ 
												'@' 
										] 
								] 
						] 
				],
				'verbs' => [ 
						'class' => VerbFilter::className (),
						'actions' => [ 
								'logout' => [ 
										'post' 
								] 
						] 
				] 
		]
		;
	}
	public function actionIndex() {
		$request = Yii::$app->request;
		
		// Parameter from another pages.
		$wa_group_id = $request->post ( 'wagroup' );
		$wa_group_id = (empty ( $wa_group_id ) ? $request->get ( 'wagroup' ) : $wa_group_id);
		
		$rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
		
		$backAction = ['wagroup/', 'rtparams'=>$rtparams];
		
		$this->addBreadCrumb ( 'WA002 จัดการกลุ่มผู้ใช้งาน', Url::to($backAction) );
		$this->addBreadCrumb ( 'จัดการผู้ใช้งาน' );
		
		// $waGroup = WA_GROUP::find ()->where ( [ 
		// 		'WA_GROUP_ID' => $wa_group_id 
		// ] )->limit ( 1 )->asArray ()->one ();
		$waGroup = WA_GROUP::findOne($wa_group_id);
		
		return $this->render ( 'index', array (
				'wagroup' => $wa_group_id,
				'waGroup' => $waGroup 
		) );
	}
	public function actionGridview() {
		$request = Yii::$app->request;
		
		// Parameter from jqGrid
		$oper = $request->post ( 'oper', '' );
		$oper = (empty ( $oper ) ? $request->get ( 'oper', '' ) : $oper);
		$page = $request->post ( 'page', '' );
		$page = (empty ( $page ) ? $request->get ( 'page', '' ) : $page);
		$rows = $request->post ( 'rows', '' );
		$rows = (empty ( $rows ) ? $request->get ( 'rows', '' ) : $rows);
		$sidx = $request->post ( 'sidx', '' );
		$sidx = (empty ( $sidx ) ? $request->get ( 'sidx', '' ) : $sidx);
		$sord = $request->post ( 'sord', '' );
		$sord = (empty ( $sord ) ? $request->get ( 'sord', '' ) : $sord);
		$id = $request->post ( 'id', '' );
		$id = (empty ( $id ) ? $request->get ( 'id', '' ) : $id);
		$isSearch = $request->post ( '_search', '' );
		$isSearch = (empty ( $isSearch ) ? $request->get ( '_search', '' ) : $isSearch);
		$isSearch = filter_var ( $isSearch, FILTER_VALIDATE_BOOLEAN );
		$filters = $request->post ( 'filters', '' );
		$filters = (empty ( $filters ) ? $request->get ( 'filters', '' ) : $filters);
		
		// Input parameter from jqGrid Form.
		$email = $request->post ( 'email' );
		$password = $request->post ( 'password' );
		$status = $request->post ( 'status' );
		
		// Parameter from another pages.
		$wa_group_id = $request->post ( 'wagroup' );
		$wa_group_id = (empty ( $wa_group_id ) ? $request->get ( 'wagroup' ) : $wa_group_id);
		// echo $wa_group_id;
		
		// response parameter to jqGrid
		$result = '';
		
		switch ($oper) {
			case 'request' :
				
				$offset = ($page - 1) * $rows;
				
				$where_causes = array ();
				if ($isSearch) {
					// ['type' => 1, 'status' => 2]
					// TODO: add condition to $where_causes.
					// $where_causes['WA_GROUP_ID'] = $wa_group_id;
				}
				
				$count = WA_USER::find ()->where ( $where_causes )->count ();
				// $result = WA_USER::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();
				
				$query = new Query ();
				$query->select ( 'WA_USER.WA_USER_ID, WA_USER.EMAIL, WA_GROUP_USER.WA_GROUP_USER_ID' )->from ( 'WA_USER' )->leftJoin ( 'WA_GROUP_USER', 'WA_USER.WA_USER_ID=WA_GROUP_USER.WA_USER_ID AND WA_GROUP_USER.WA_GROUP_ID=' . $wa_group_id )->where ( $where_causes )->orderBy ( $sidx . ' ' . $sord )->offset ( $offset )->limit ( $rows );
				$result = $query->all ();
				$command = $query->createCommand ();
				$result = $command->queryAll ();
				$countResult = count($result);
                $seq = $rows * ($page - 1);
				
				$response = new jqGridResponse ();
				$response->page = $page;
				$response->total = intval ( ceil ( $count / $rows ) );
				$response->records = $count;
				
				for($i = 0; $i <$countResult; $i ++) {
					$seq++;

					$status = (empty ( $result [$i] ['WA_GROUP_USER_ID'] ) ? 'C' : 'A');
					array_push ( $response->rows, array (
							'id' => $result [$i] ['WA_USER_ID'],
							'cell' => array (
									$seq,
									$result [$i] ['EMAIL'],
									$this->tempPassword,
									$status,
									'edit'
							) 
					) );
				}
				$result = json_encode ( $response );
				break;
			case 'edit':
				// To update an existing customer record
				$waUser = WA_USER::findOne ( $id );
				$waUser->EMAIL = $email;
				if ($password != $this->tempPassword) {
					$waUser->PASSWORD = md5($password);
				}
				$waUser->LAST_UPD_USER_ID = '1';
				$waUser->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

				$response = new jqGridResponse();
				if ($waUser->save ()) { // equivalent to $customer->insert();
					$status = Utils::getStatus ( $status );
					
					if ($status == 'A') {
						// TODO: Add new record to WA_GROUP_USER
						$rs1 = WA_GROUP_USER::find ()->where ( [ 
								'WA_GROUP_ID' => $wa_group_id,
								'WA_USER_ID' => $id 
						] )->asArray ()->all ();
						// echo count($rs1);
						if (count ( $rs1 ) < 1) {
							$waGroupUser = new WA_GROUP_USER ();
							$waGroupUser->WA_GROUP_USER_ID = WA_GROUP_USER::getNewID ();
							$waGroupUser->WA_GROUP_ID = $wa_group_id;
							$waGroupUser->WA_USER_ID = $id;
							$waGroupUser->CREATE_USER_ID = '1';
							$waGroupUser->CREATE_TIME = new \yii\db\Expression('SYSDATE');
							$waGroupUser->LAST_UPD_USER_ID = '1';
							$waGroupUser->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
							if ($waGroupUser->save ()) {
								$response->success(['id'=>$waUser->WA_USER_ID]);
							} else {
								$response->error($waGroupUser->getErrors());
							}
						} else {
							$response->success(['id'=>$waUser->WA_USER_ID]);
						}
					} else if ($status == 'C') {
						// TODO: Remove record from WA_GROUP_USER
						// $waGroupUser = WA_GROUP_USER::find()->where(['WA_GROUP_ID'=>$wa_group_id, 'WA_USER_ID'=>$id]);
						// $waGroupUser->delete();
						
						$query = new Query ();
						$query->createCommand ()->delete ( 'WA_GROUP_USER', 'WA_GROUP_ID=' . $wa_group_id . ' AND WA_USER_ID=' . $id )->execute ();
						
						$response->success(['id'=>$waUser->WA_USER_ID]);
					}
					// $waGroupUser = WA_GROUP_USER::find();
				} else {
					$response->error($waUser->getErrors());
				}

				$result = $response->response_encode();
				break;
			case 'add':
				$transaction = Yii::$app->db->beginTransaction();

				try {
					$waUser = new WA_USER ();
					$waUser->WA_USER_ID = WA_USER::getNewID();
					$waUser->USER_NAME_EN = $email;
					$waUser->USER_NAME_TH = $email;
					$waUser->EMAIL = $email;
					$waUser->PASSWORD = md5 ( $password );
					$waUser->STATUS = Utils::getStatus ( $status );
					$waUser->CREATE_USER_ID = '1';
					$waUser->CREATE_TIME = new \yii\db\Expression('SYSDATE');
					$waUser->LAST_UPD_USER_ID = '1';
					$waUser->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

					$response = new jqGridResponse();
					if ($waUser->save()) {
						$waGroupUser = new WA_GROUP_USER();
						$waGroupUser->WA_GROUP_USER_ID = WA_GROUP_USER::getNewID();
						$waGroupUser->WA_GROUP_ID = $wa_group_id;
						$waGroupUser->WA_USER_ID = $waUser->WA_USER_ID;
						$waGroupUser->CREATE_USER_ID = '1';
						$waGroupUser->CREATE_TIME = new \yii\db\Expression('SYSDATE');
						$waGroupUser->LAST_UPD_USER_ID = '1';
						$waGroupUser->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

						if ($waGroupUser->save()) {
							$transaction->commit();
							$response->success(['id'=>$waUser->WA_USER_ID]);	
						} else {
							$transaction->rollback();
							$response->error($waUser->getErrors());
						}
					} else {
						$transaction->rollback();
						$response->error($waUser->getErrors());
					}
				} catch (Exception $e) {
					$transaction->rollback();
                    $response->error([Utils::getOracleErrorMsg($e->errorInfo[1])]);
                }

                $result = $response->response_encode();
				break;
			case 'del':
				try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    WA_GROUP_USER::deleteAll(['in', 'WA_USER_ID', $arrId]);
                    WA_USER::deleteAll(['in', 'WA_USER_ID', $arrId]);

                    $response->success(['message' => 'ลบข้อมูลสำเร็จ']);
                } catch (Exception $e) {
                    $response->error(Utils::getOracleErrorMsg($e->errorInfo[1]));
                }
                
                $result = $response->response_encode();
				break;
		}
		
		echo $result;
	}
}
