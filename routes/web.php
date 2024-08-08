<?php

use App\Http\Controllers\Admin\{
    AdminController,
    AdminCourseController,
    FolderController,
    AdminAnnouncementsController,
    AdminCertificationsController,
    AdminCompaniesController,
    AdminCourseCategoriesController,
    AdminCourseEvaluationController,
    AdminCourseSectionsController,
    AdminDynamicQuestionsController,
    AdminDynamicAlternativeController,
    AdminEventsController,
    AdminExamsController,
    AdminFreeCoursesController,
    AdminRoomsController,
    AdminUsersController,
    AdminOwnerCompaniesController,
    AdminMiningUnitsController,
    AdminSectionChaptersController,
    AdminSurveyController,
    AdminSurveyGroupController,
    AdminSurveyOptionController,
    AdminSurveyStatementController,
    CourseModuleController,
    CourseTypeController,
    FileController,
    SpecCourseController,
    SpecCourseEventsController,
    AdminSliderImageController,
    SpecCourseGroupEventsController,
    GroupParticipantController,
    TestController,
    AssignmentController,
    ForgettingCurveController,
    CurveStepController,
    FcVideoController,
    FcVideoQuestionController,
    FreeCourseLiveController,
    ReportForgettingCurveController,
    EventFreeCourseLiveController,
    WebinarController,
    WebinarEventCertificationsController,
    WebinarEventController,
    SettingsController,
    NewsController,
    AdminCurveQuestionController,
    AdminCourseExamsController,
    AdminCourseUsersController,
    PrincipalBannerController
};
use App\Http\Controllers\Aula\Common\{
    AulaHomeController,
    AulaCourseController,
    AulaFolderController,
    AulaProfileController,
    AulaSignaturesController,
    AulaOnlineLessonController,
    AulaSpecCourseController,
    AulaSpecOnlineLessonController,
    AulaSpecFileController,
    AulaFreeCourseLiveResourcesController,
    AulaWebinarController,
    AulaWebinarResourcesController,
    AulaWebinarOnlineLessonController,
    AulaWebinarEventController
};
use App\Http\Controllers\Aula\Participant\{
    QuizController,
    AulaEvaluationController,
    AulaFreeCourseController,
    AulaMyProgressController,
    AulaSpecEvaluationController,
    AulaSurveysController,
    SpecQuizController,
    AulaSpecAssignmentController,
    AulaForgettingCurveController,
    AulaFcInstanceController,
    AulaFcEvaluationController,
    AulaFreeCourseLiveController,
    AulaFreeCourseLiveOnlineLessonController,
    AulaFreeCourseLiveEvaluationController,
    AulaFreeCourseLiveQuizController,
    AulaFreeCourseEventController,
    AulaDocParticipantController,
    AulaFcVideoController,
    AulaFreeCourseEvaluationController
};
use App\Http\Controllers\Aula\Instructor\{
    AulaEventsInsController,
    AulaInsAssignmentsController,
    AulaSpecCoursesInsController,
    AulaSpecModuleController,
    AulaUserSurveyInstructorController,
    AulaInstructorInformationController
};

use App\Http\Controllers\Aula\Company\{
    AulaUserCompanyController,
    AulaDocCompanyController,
    AulaKpisCompanyController,
    AulaUserEvaluationsCompanyController,
};

use App\Http\Controllers\Aula\Supervisor\{
    CertificationSupervisorController,
    EventsController as SupervisorEventsController,
    SupervisorKpiController,
};


use App\Http\Controllers\Home\{HomeAboutController, HomeController, HomeCourseController, HomeCertificationController, HomeFreeCourseController, HomeWebinarController};

use App\Http\Controllers\Auth\{LoginController, RegisterController};
use App\Http\Controllers\Common\Certificate\{CertificateController};
use App\Http\Controllers\Pdf\{
    PdfCertificationController
};
use App\Http\Controllers\Reports\{ProfileSurveyReportController, SurveysReportController};
use App\Http\Controllers\Security\{AulaEventsSecurController};
use Illuminate\Support\Facades\{Auth, Route};


Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home.index');
    Route::get('/obtener-contenido-de-registro/{place}', 'getRegisterModalContent')->name('home.getRegisterModalContent');
});

Route::controller(TestController::class)->group(function () {
    Route::get('/test-2', 'TestAssignment');
});

// Route::controller(LoginController::class)->group(function () {
//     Route::get('/', 'showLoginForm')->name('login');
//     // Route::post('/login/validate-attempt', 'validateAttempt')->name('login.validateAttempt');
// });

Route::controller(LoginController::class)->group(function () {

    Route::get('/login/{location?}/{redirect?}', 'showLoginForm')->name('login');
    Route::post('/login/validate-attempt', 'validateAttempt')->name('login.validateAttempt');
    Route::post('/login/{redirect?}', 'login');
});


Route::controller(RegisterController::class)->group(function () {

    Route::get('/registro/validate-dni', 'validateDni')->name('register.validateDni');
    Route::get('/registro/{location?}/{redirect?}', 'showRegistrationForm')->name('register.show');
    Route::post('/registrar-usuario/{location?}/{redirect?}', 'register')->name('home.user.register');
    Route::post('/registrar-usuario-externo/{location?}/{redirect?}', 'registerExternal')->name('home.user.registerExternal');
});

Route::controller(HomeCourseController::class)->group(function () {

    Route::get('/cursos', 'index')->name('home.courses.index');
    Route::get('/cursos/{course}', 'show')->name('home.courses.show');
});

Route::controller(HomeFreeCourseController::class)->group(function () {

    Route::get('/cursos-libres/categorías', 'index')->name('home.freecourses.categories.index');
    Route::get('/cursos-libres/categoría/{category}', 'show')->name('home.freecourses.show');
    Route::get('/cursos-libres/obtener-informacion/{freeCourse}', 'getInformation')->name('home.freecourses.getInformation');
});


// Route::group(['middleware' => 'check.external.user'], function () {
//     Route::controller(HomeWebinarController::class)->group(function () {
//         Route::get('/webinars', 'index')->name('home.webinar.index');
//         Route::get('/webinars/{webinar}', 'show')->name('home.webinar.show');
//     });
// });


Route::controller(HomeAboutController::class)->group(function () {

    Route::get('/nosotros', 'index')->name('home.about.index');
    Route::get('/obtener-información-instructor/{instructor}', 'getInformationInstructor')->name('home.about.getInformationInstructor');
});

Route::controller(HomeCertificationController::class)->group(function () {

    Route::post('/incribir-evento/{event}', 'UserCertificationSelfRegister')->name('home.certifications.userSelfRegister');
    Route::post('/incribir-evento-externo/{event}', 'UserExternalCertificationSelfRegister')->name('home.certifications.userExternalSelfRegister');
    Route::post('/solicitar-inscripcion-a-curso/{course}', 'requestRegistrationCourse')->name('home.certifications.requestRegistrationCourse');
});

Auth::routes(['register' => false]);






// --------------- CERTIFICATION -----------------
// ------ certifications.*

// Route::group(['prefix' => 'certificados', 'as' => 'certifications.'], function () {

//     Route::controller(CertificateController::class)->group(function () {

//         Route::get('/', 'index')->name('index');
//     });
// });

// Route::group(['prefix' => 'pdf', 'as' => 'pdf.'], function () {

//     // ------ pdf.*
//     Route::controller(PdfCertificationController::class)->group(function () {

//         Route::get('/exportar-certificado/{certification}', 'certificationPdf')->name('export.certification');
//         Route::get('/exportar-compromiso/{certification}/unidad-minera/{miningUnit}', 'commitmentPdf')->name('export.commitment');
//         Route::get('/exportar-certificado-externo/{certification}', 'extCertificationPdf')->name('export.ext_certification');
//         Route::get('/exportar-certificado-webinar/{certification}', 'webCertificationPdf')->name('export.web_certification');
//         Route::get('/descargar-archivo/{file}', 'downloadFile')->name('download.file');
//         Route::get('/generar-pdf-evaluación-de-participante/{certification}', 'examPdf')->name('examForParticipant');

//         //* ----------- CURSOS LIBRES -------------
//         Route::get('/exportar-certificado-curso-libre/{certification}', 'freecourseCertificationPdf')->name('export.freecoursecertification');
//     });
// });




Route::group(['middleware' => ['auth', 'check.valid.user']], function () {

    // RUTAS DE LA INTERFAZ ADMINISTRADOR ------------------

    Route::group([
        'middleware' => 'check.role:admin,super_admin',
        'prefix' => 'admin',
        'as' => 'admin.'
    ], function () {

        // ---- ADMIN DASHBOARD PRINCIPAL VIEW --------

        Route::get('/inicio', [AdminController::class, 'index'])->name('home.index');

        // ------------- ONLY ADMIN -------------------

        Route::group(['middleware' => 'check.role:admin,super_admin'], function () {


            // --------------- SETTINGS ----------------

            Route::group(['prefix' => 'configuraciones', 'as' => 'settings.'], function () {

                Route::controller(SettingsController::class)->group(function () {

                    Route::get('/', 'index')->name('index');
                    Route::post('/actualizar-configuración/{config}', 'updateConfig')->name('config.update');
                    Route::post('/actualizar-logo/{config}', 'updateLogo')->name('config.update.logo');
                    Route::delete('/eliminar-logo/{config}', 'destroyLogo')->name('config.destroy.logo');
                });

                Route::controller(PrincipalBannerController::class)->group(function () {

                    Route::group(['prefix' => 'banner-principal', 'as' => 'pbanner.'], function () {

                        Route::get('/editar/{banner}', 'edit')->name('edit');
                        Route::get('/obtener-orden-de-banners', 'getBannersOrder')->name('getBannersOrder');
                        Route::post('/registrar', 'store')->name('store');
                        Route::post('/actualizar/{banner}', 'update')->name('update');
                        Route::delete('/eliminar/{banner}', 'destroy')->name('destroy');
                    });
                });
            });

            // --------------- USERS -------------------------

            Route::group(['prefix' => 'usuarios'], function () {

                Route::controller(AdminUsersController::class)->group(function () {

                    Route::get('/', 'index')->name('users.index');
                    Route::get('/editar/{user}', 'edit')->name('user.edit');
                    Route::get('/descargar-documento/{file}', 'downloadDocument')->name('participant.downloadDocument');
                    Route::get('/obtener-contenido-de-documentos/{participant}', 'getDocsContent')->name('participant.getDocsContent');

                    Route::get('/obtener-información-del-instructor/{instructor}', 'getInstructorInformation')->name('instructor.information.edit');
                    Route::post('/actualizar-información-del-instructor/{instructor}', 'updateInstructorInformation')->name('instructor.information.update');

                    Route::post('/registrar-documento/{participant}', 'publishDocsForParticipant')->name('participant.publishDocForParticipant');
                    Route::post('/registrar/validar-email', 'registerValidateEmail')->name('users.validateEmail');
                    Route::post('/editar/validar-email', 'editValidateEmail')->name('user.editValidateEmail');
                    Route::post('/registrar', 'store')->name('user.store');
                    Route::post('/actualizar/{user}', 'update')->name('user.update');
                    Route::delete('/eliminar/{user}', 'destroy')->name('user.delete');
                    Route::delete('/eliminar-documentos/{file}/{participant}', 'destroyDocument')->name('participant.deleteDocument');

                    Route::get('/descargar-plantilla-registro-masivo', 'downloadImportTemplate')->name('user.download.register.template');
                    Route::post('/registro-masivo', 'massiveStore')->name('users.massive.store');

                    Route::get('/exportar-excel', 'exportExcel')->name('users.exportExcel');
                });
            });

            // ----------------- COMPANIES --------------------

            Route::group(['prefix' => 'empresas'], function () {

                Route::controller(AdminCompaniesController::class)->group(function () {

                    Route::get('/', 'index')->name('companies.index');
                    Route::get('/editar/{company}', 'edit')->name('companies.edit');
                    Route::get('/descargar-documento/{file}', 'downloadDocument')->name('companies.downloadDocument');
                    Route::get('/obtener-contenido-de-documentos/{company}', 'getDocsContent')->name('companies.getDocsContent');
                    Route::post('/registrar-documento/{company}', 'publishDocsInCompany')->name('companies.publishDocsInCompany');
                    Route::post('/registrar', 'store')->name('companies.store');
                    Route::post('/editar/validar-ruc', 'EditvalidateRuc')->name('companies.validateRuc');
                    Route::post('/actualizar/{company}', 'update')->name('companies.update');
                    Route::delete('/eliminar/{company}', 'destroy')->name('companies.delete');
                    Route::delete('/eliminar-documentos/{file}/{company}', 'destroyDocument')->name('companies.deleteDocument');

                    Route::get('/exportar-excel', 'ExportExcel')->name('companies.exportExcel');
                });
            });

            // ---------------- OWNER COMPANIES ---------------

            Route::group(['prefix' => 'empresas-titulares'], function () {

                Route::controller(AdminOwnerCompaniesController::class)->group(function () {

                    Route::get('/', 'index')->name('ownerCompanies.index');
                    Route::get('/editar/{company}', 'edit')->name('ownerCompany.edit');
                    Route::post('/validar-registro', 'registerValidate')->name('ownerCompany.registerValidate');
                    Route::post('/validar-edición', 'editValidate')->name('ownerCompanies.editValidate');
                    Route::post('/registrar', 'store')->name('ownerCompanies.store');
                    Route::post('/actualizar/{company}', 'update')->name('ownerCompany.update');
                    Route::delete('/eliminar/{company}', 'destroy')->name('ownerCompany.delete');

                    Route::get('/exportar-excel', 'exportExcel')->name('ownerCompanies.exportExcel');
                });
            });

            /* ---------------- MINING UNITS ----------------------*/

            Route::group(['prefix' => 'unidades-mineras'], function () {

                Route::controller(AdminMiningUnitsController::class)->group(function () {

                    Route::get('/', 'index')->name('miningUnits.index');
                    Route::get('/editar/{miningUnit}', 'getDataEdit')->name('miningUnits.getDataEdit');
                    Route::post('/registrar', 'store')->name('miningUnits.store');
                    Route::post('/actualizar/{miningUnit}', 'update')->name('mininUnits.update');
                    Route::delete('/eliminar/{miningUnit}', 'destroy')->name('miningUnits.delete');

                    Route::get('/exportar-excel', 'exportExcel')->name('miningUnits.exportExcel');
                });
            });

            // --------------- ROOMS -------------------------

            Route::group(['prefix' => 'salas'], function () {

                Route::controller(AdminRoomsController::class)->group(function () {

                    Route::get('/', 'index')->name('rooms.index');
                    Route::get('/editar/{room}', 'edit')->name('room.edit');
                    Route::post('/registrar', 'store')->name('rooms.store');
                    Route::post('/registrar/validar-nombre', 'registerValidateName')->name('rooms.registerValidateName');
                    Route::post('/editar/validar-nombre', 'editValidateName')->name('rooms.editValidateName');
                    Route::post('/actualizar/{room}', 'update')->name('room.update');
                    Route::delete('/eliminar/{room}', 'destroy')->name('rooms.delete');

                    Route::get('/exportar-excel', 'exportExcel')->name('rooms.exportExcel');
                });
            });

            // -------------- COURSE TYPES -----------------
            //--- admin.coursetypes.*

            Route::group(['prefix' => 'tipos-de-cursos', 'as' => 'coursetypes.'], function () {

                Route::controller(CourseTypeController::class)->group(function () {

                    Route::get('/', 'index')->name('index');
                    Route::get('/editar/{coursetype}', 'edit')->name('edit');
                    Route::post('/registrar', 'store')->name('store');
                    Route::post('/actualizar/{coursetype}', 'update')->name('update');
                    Route::delete('/eliminar/{coursetype}', 'destroy')->name('destroy');

                    Route::get('/exportar-excel', 'exportExcel')->name('exportExcel');
                });
            });

            // --------------- COURSES ----------------------
            //--- admin.courses.*
            Route::group(['prefix' => 'cursos', 'as' => 'courses.'], function () {

                Route::controller(AdminCourseController::class)->group(function () {

                    Route::get('/', 'index')->name('index');
                    Route::get('/editar/{course}', 'edit')->name('edit');
                    Route::get('/ver/{course}', 'show')->name('show');
                    Route::post('/registrar', 'store')->name('store');
                    Route::post('/actualizar/{course}', 'update')->name('update');
                    Route::delete('/eliminar/{course}', 'destroy')->name('delete');

                    Route::get('/exportar-excel', 'exportExcel')->name('exportExcel');
                });


                Route::group(['prefix' => 'carpetas'], function () {

                    Route::controller(FolderController::class)->group(function () {

                        Route::get('/ver-carpeta/{folder}', 'show')->name('folder.view');
                        Route::post('/crear-carpeta/{course}', 'store')->name('folder.create');
                        Route::post('/subfolder/{folder}', 'storeSubfolder')->name('subfolder.create');
                        Route::patch('/{folder}/actualizar', 'update')->name('folder.update');
                        Route::delete('/{folder}/eliminar', 'destroy')->name('folder.destroy');

                        // ------FOLDER FILES --------------

                        Route::get('/{folder}/archivos', 'showFiles')->name('files.index');
                        Route::get('/archivo/{file}/descargar', 'downloadFile')->name('folders.file.download');
                        Route::post('/{folder}/añadirArchivo', 'storeFile')->name('folders.file.store');
                        Route::delete('/archivo/{file}/eliminar', 'destroyFile')->name('folders.file.destroy');
                    });
                });
            });

            // -------------- SPEC COURSES -------------------

            //----- admin.specCourses.* --------------
            Route::group(['prefix' => 'cursos-de-especializacion', 'as' => 'specCourses.'], function () {

                Route::controller(SpecCourseController::class)->group(function () {

                    Route::get('/', 'index')->name('index');
                    Route::get('/ver/{specCourse}', 'show')->name('show');
                    Route::get('/editar/{specCourse}', 'edit')->name('edit');
                    Route::post('/registrar', 'store')->name('store');
                    Route::post('/actualizar/{specCourse}', 'update')->name('update');
                    Route::delete('/eliminar/{specCourse}', 'destroy')->name('destroy');

                    // ------FOLDER FILES --------------

                    Route::get('/{specCourse}/archivos', 'showFiles')->name('files.index');
                    Route::get('/archivo/{file}/descargar', 'downloadFile')->name('file.download');
                    Route::post('/{specCourse}/añadirArchivo', 'storeFile')->name('file.store');
                    Route::delete('/archivo/{file}/{specCourse}/eliminar', 'destroyFile')->name('file.destroy');
                    Route::get('/exportar-excel', 'exportExcel')->name('exportExcel');
                });

                //----- admin.specCourses.modules.* --------------
                Route::group(['prefix' => 'módulos', 'as' => 'modules.'], function () {

                    Route::controller(CourseModuleController::class)->group(function () {

                        Route::get('/editar/{module}', 'edit')->name('edit');
                        Route::post('/registrar/{specCourse}', 'store')->name('store');
                        Route::post('/actualizar-orden/{module}', 'updateOrder')->name('updateOrder');
                        Route::post('/actualizar/{module}', 'update')->name('update');
                        Route::delete('/eliminar/{module}', 'destroy')->name('destroy');

                        // -------------- FILES ------------

                        Route::get('/obtener-lista-de-archivos/{module}', 'getFiles')->name('getFiles');
                        Route::get('/descargar-archivo/{file}', 'downloadFile')->name('downloadFile');
                        Route::post('/registrar-archivos/{module}', 'storeFiles')->name('storeFiles');
                        Route::delete('/eliminar-archivo/{file}', 'destroyFiles')->name('destroyFile');
                    });
                });

                // ---- admin.specCourses.groupEvents.*--------

                Route::group(['prefix' => 'grupos-de-eventos', 'as' => 'groupEvents.'], function () {

                    Route::controller(SpecCourseGroupEventsController::class)->group(function () {

                        Route::get('/obtener-grupos-de-eventos/{specCourse}', 'getDataTable')->name('getDataTable');
                        Route::get('/editar/{groupEvent}', 'edit')->name('edit');
                        Route::get('/ver/{groupEvent}', 'show')->name('show');
                        Route::post('/registrar/{specCourse}', 'store')->name('store');
                        Route::post('/actualizar/{groupEvent}', 'update')->name('update');
                        Route::delete('/eliminar/{groupEvent}', 'destroy')->name('destroy');
                    });

                    Route::group(['prefix' => 'grupos-de-participantes', 'as' => 'groupParticipants.'], function () {

                        Route::controller(GroupParticipantController::class)->group(function () {

                            Route::get('/obtener-grupos-de-participantes/{groupEvent}', 'getDataTable')->name('getDataTable');
                            Route::get('/obtener-posibles-participantes/{groupParticipant}', 'getPotentialParticipants')->name('getPotentialParticipants');
                            Route::get('/editar/{groupParticipant}', 'edit')->name('edit');
                            Route::get('/ver/{groupParticipant}', 'show')->name('show');
                            Route::post('/anadir-participantes-al-grupo/{groupParticipant}', 'addParticipantsOnGroup')->name('addParticipantsOnGroup');
                            Route::post('/registrar/{groupEvent}', 'store')->name('store');
                            Route::post('/actualizar/{groupParticipant}', 'update')->name('update');
                            Route::delete('/eliminar-participantes-del-grupo/{participant}/{groupParticipant}', 'deleteParticipantOnGroup')->name('deleteParticipantOnGroup');
                            Route::delete('/eliminar/{groupParticipant}', 'destroy')->name('destroy');
                        });
                    });
                });


                //----- admin.specCourses.events.* --------------
                Route::group(['prefix' => 'eventos', 'as' => 'events.'], function () {


                    Route::controller(SpecCourseEventsController::class)->group(function () {

                        Route::get('/obtener-eventos/{module}/{groupEvent}', 'getDataTable')->name('getDataTable');
                        Route::get('/crear/obtener-data', 'create')->name('create');
                        Route::get('/editar/{event}', 'edit')->name('edit');
                        Route::get('/ver/{event}', 'show')->name('show');

                        Route::get('/obtener-asignaciones/{event}', 'getDatatableAssignment')->name('getDatatableAssignment');
                        Route::post('/registrar-asignacion/{event}', 'storeAssignment')->name('storeAssignment');

                        Route::post('/registrar/{module}/{groupEvent}', 'store')->name('store');
                        Route::post('/actualizar/{event}', 'update')->name('update');
                        Route::delete('/eliminar/{event}', 'destroy')->name('destroy');
                    });

                    Route::group(['prefix' => 'asignaciones', 'as' => 'assignments.'], function () {

                        Route::controller(AssignmentController::class)->group(function () {

                            Route::get('/obtener-asignaciones/{event}', 'getDataTable')->name('getDataTable');
                            Route::get('/editar-asignacion/{assignment}', 'edit')->name('edit');
                            Route::get('/obtener-documentos-de-la-asignación/{assignment}', 'getDocuments')->name('getDocuments');
                            // Route::get('/ver/{assignment}', 'show')->name('show');
                            Route::get('/descargar-documento-de-la-asignación/{file}', 'dowloadFile')->name('dowloadFile');

                            Route::post('/subir-documentos-de-la-asignación/{assignment}', 'uploadDocuments')->name('uploadDocuments');
                            Route::post('/registrar-asignacion/{event}', 'store')->name('store');
                            Route::post('/actualizar-asignacion/{assignment}', 'update')->name('update');
                            Route::delete('/eliminar-asignacion/{assignment}', 'destroy')->name('destroy');
                            Route::delete('/eliminar-documento-de-la-asignación/{file}/{assignment}', 'destroyFile')->name('destroyFile');
                        });
                    });
                });
            });

            // --------------- FREE COURSES -----------------

            Route::group(['prefix' => 'cursos-libres', 'as' => 'freeCourses.'], function () {

                Route::controller(AdminFreeCoursesController::class)->group(function () {

                    Route::get('/', 'index')->name('index');
                    Route::get('/obtener-categorias', 'getCategoriesRegisterCourse')->name('getCategoriesRegister');
                    Route::get('/obtener-examenes-del-curso/{course}', 'getExamsThatBelongToCourse')->name('getExamsThatBelongToCourse');
                    Route::get('/curso/{course}', 'show')->name('courses.index');
                    Route::get('/editar/{course}', 'edit')->name('courses.edit');
                    Route::post('/registrar', 'store')->name('courses.store');
                    Route::post('/actualizar/{course}', 'update')->name('courses.update');
                    Route::post('/curso/eliminar/{course}', 'destroy')->name('courses.delete');


                    Route::group(['prefix' => 'archivos', 'as' => 'files.'], function () {

                        Route::get('/{course}', 'getFilesDataTable')->name('index');
                        Route::get('/descargar-archivo/{file}', 'downloadFile')->name('download');
                        Route::post('/registrar/{course}', 'storeFiles')->name('store');
                        Route::delete('/eliminar-archivo/{course}/{file}', 'destroyFile')->name('destroy');
                    });
                });

                Route::group(['prefix' => 'users', 'as' => 'users.'], function () {

                    Route::controller(AdminCourseUsersController::class)->group(function () {
                        Route::get('/obtener-participantes/{course}', 'getDataTable')->name('getDataTable');
                        Route::get('/obtener-usuarios/{course}', 'getUsersList')->name('getUsersList');
                        Route::post('/registrar-participante/{course}', 'store')->name('store');
                        Route::post('/actualizar-desbloqueo/{productCertification}', 'updateUnlock')->name('updateUnlock');
                        Route::post('/restablecer-certificado/{productCertification}', 'resetCertification')->name('resetCertification');
                        Route::delete('/eliminar-usuariio/{productCertification}', 'destroy')->name('destroy');
                    });
                });


                Route::group(['prefix' => 'categorías', 'as' => 'categories.'], function () {

                    Route::controller(AdminCourseCategoriesController::class)->group(function () {

                        Route::get('/{category}', 'index')->name('index');
                        Route::get('/editar/{category}', 'edit')->name('edit');
                        Route::post('/registrar', 'store')->name('store');
                        Route::post('/actualizar/{category}', 'update')->name('update');
                        Route::post('/eliminar/{category}', 'destroy')->name('delete');
                    });
                });

                Route::group(['prefix' => 'secciones', 'as' => 'sections.'], function () {

                    Route::controller(AdminCourseSectionsController::class)->group(function () {

                        Route::get('/editar/{section}', 'edit')->name('edit');
                        Route::get('/obtener-examenes-de-la-seccion/{section}', 'getExam')->name('getExam');
                        Route::post('/relacionar-exame-para-secion/{section}', 'relationExam')->name('relationExam');
                        Route::post('/curso/{course}/secciones/registrar', 'store')->name('store');
                        Route::post('/actualizar-orden/{section}', 'updateOrder')->name('updateOrder');
                        Route::post('/actualizar/{section}', 'update')->name('update');
                        Route::post('/eliminar/{section}', 'destroy')->name('delete');

                        Route::delete('/eliminar-evaluation/{fcEvaluation}', 'destroyEvaluation')->name('deleteEvaluation');
                    });
                });

                Route::group(['prefix' => 'capítulos', 'as' => 'chapters.'], function () {

                    Route::controller(AdminSectionChaptersController::class)->group(function () {

                        Route::get('/obtener-capítulos/{section}', 'getDataTable')->name('getDataTable');
                        Route::get('/editar/{chapter}', 'edit')->name('edit');
                        Route::get('/obtener-video/{chapter}', 'getVideoData')->name('getVideoData');

                        Route::get('/obtener-contenido-capitulo/{chapter}', 'getContentDetail')->name('getContentDetail');
                        Route::get('/descargar-archivo/{file}', 'downloadFile')->name('downloadFile');
                        Route::get('/obtener-archivos-capitulo/{chapter}', 'getFilesData')->name('getFilesData');

                        Route::post('/sección/{section}/registrar-capítulo', 'store')->name('store');
                        Route::post('/actualizar/{chapter}', 'update')->name('update');
                        Route::post('/capítulos/eliminar/{chapter}', 'destroy')->name('delete');

                        Route::post('/actualizar-contenido/{chapter}', 'updateContent')->name('updateContent');
                        Route::post('/registrar-archivos/{chapter}', 'storeFiles')->name('storeFiles');

                        Route::delete('/eliminar-video/{chapter}', 'deleteVideo')->name('deleteVideo');
                        Route::delete('/eliminar-archivo/{file}/{chapter}', 'deleteFile')->name('deleteFile');

                    });
                });

                Route::group(['prefix' => 'examenes', 'as' => 'exams.'], function () {

                    Route::controller(AdminCourseExamsController::class)->group(function () {

                        Route::get('/ver/{exam}', 'show')->name('show');
                        Route::get('/ver-enunciado/{question}', 'showQuestion')->name('questions.show');
                        Route::get('/obtener-examenes/{course}', 'getDataTable')->name('getDataTable');
                        Route::get('/editar/{exam}', 'edit')->name('edit');

                        Route::post('/registrar-enunciado/{exam}', 'storeQuestion')->name('questions.store');
                        Route::post('/actualizar-enunciado/{question}', 'updateQuestion')->name('questions.update');
                        Route::post('/eliminar-enunciado/{question}', 'destroyQuestion')->name('questions.destroy');
                        Route::post('/registrar/{course}', 'store')->name('store');
                        Route::post('/actualizar/{exam}', 'update')->name('update');

                        Route::delete('/eliminar/{exam}', 'destroy')->name('delete');
                    });
                });

                Route::group(['prefix' => 'evaluaciones', 'as' => 'evaluations.'], function () {
                    Route::controller(AdminCourseEvaluationController::class)->group(function () {

                        Route::get('/evaluacion/{fcEvaluation}', 'index')->name('index');
                        Route::post('/actualizar-evaluacion/{fcEvaluation}', 'update')->name('update');
                    });
                });
            });

            // --------------- LIVE FREE COURSES --------------

            // -------- admin.freeCourseLive.*
            Route::group([
                'prefix' => 'cursos-libres-en-vivo',
                'as' => 'freeCourseLive.'
            ], function () {

                Route::controller(FreeCourseLiveController::class)->group(function () {

                    Route::get('/', 'index')->name('index');
                    Route::get('/ver/{course}', 'show')->name('show');
                    Route::get('/editar/{course}', 'edit')->name('edit');
                    Route::post('/registrar', 'store')->name('store');
                    Route::post('/actualizar/{course}', 'update')->name('update');
                    Route::delete('/eliminar/{course}', 'destroy')->name('destroy');

                    Route::get('/exportar-excel', 'exportExcel')->name('exportExcel');

                    // ------ FILES ----------

                    Route::group([
                        'prefix' => 'archivos',
                        'as' => 'files.'
                    ], function () {

                        Route::get('/obtener-archivos/{course}', 'getFilesDataTable')->name('index');
                        Route::get('/descargar-archivo/{file}', 'downloadFile')->name('download');
                        Route::post('/registrar-archivos/{course}', 'storeFiles')->name('store');
                        Route::delete('/eliminar-archivo/{course}/{file}', 'destroyFile')->name('destroy');
                    });


                    Route::group(['prefix' => 'eventos-del-curso-libre-en-vivo', 'as' => 'eventsFcl.'], function () {

                        Route::controller(EventFreeCourseLiveController::class)->group(function () {

                            Route::get('/eventos/{course}', 'index')->name('index');
                            Route::get('/registrar/{course}', 'store')->name('store');
                            Route::get('/crear/obtener-data', 'create')->name('create');
                        });
                    });
                });
            });

            // -------------------- EXAMS ----------------------

            Route::group(['prefix' => 'examenes', 'as' => 'exams.'], function () {

                Route::controller(AdminExamsController::class)->group(function () {

                    Route::get('/', 'index')->name('index');
                    Route::get('/editar/{exam}', 'edit')->name('edit');
                    Route::post('/registrar', 'store')->name('store');
                    Route::post('/actualizar/{exam}', 'update')->name('update');
                    Route::post('/eliminar/{exam}', 'destroy')->name('destroy');

                    Route::get('/exportar-excel', 'exportExcel')->name('exportExcel');
                });

                Route::controller(AdminDynamicQuestionsController::class)->group(function () {

                    Route::get('/ver/{exam}', 'index')->name('showQuestions');
                    Route::get('/ver-enunciado/{question}', 'show')->name('questions.show');
                    Route::get('/obtener-tipo-de-enunciado', 'getQuestionType')->name('questions.getType');
                    Route::post('/registrar-enunciado/{exam}', 'store')->name('questions.store');
                    Route::post('/actualizar-enunciado/{question}', 'update')->name('questions.update');
                    Route::post('/eliminar-enunciado/{question}', 'destroy')->name('questions.destroy');
                });

                Route::controller(AdminDynamicAlternativeController::class)->group(function () {

                    Route::post('/alternativa/{alternative}/eliminar', 'destroy')->name('alternatives.destroy');
                    Route::post('/alternativa/{alternative}/eliminar-archivo', 'destroyFile')->name('alternatives.deleteFile');
                });
            });

            // ------------------ EVENTS ------------------------

            Route::group(['prefix' => 'eventos'], function () {

                Route::controller(AdminEventsController::class)->group(function () {

                    Route::get('/', 'index')->name('events.index');
                    Route::get('/ver/{event}', 'show')->name('events.show');
                    Route::get('/crear/obtener-data', 'create')->name('events.create');
                    Route::get('/validar-enunciados-puntuación/', 'validateQuestionsScore')->name('events.validateQuestionsScore');
                    Route::get('/editar-evento/{event}/obtener-data', 'edit')->name('events.edit');
                    Route::get('/obtener-usuarios/{event}', 'getUsersTable')->name('events.getUsersTable');
                    Route::post('/registrar', 'store')->name('events.store');
                    Route::post('/actualizar/{event}', 'update')->name('events.update');
                    Route::delete('/eliminar/{event}', 'destroy')->name('events.destroy');
                });

                Route::controller(AdminCertificationsController::class)->group(function () {

                    Route::get('/ver-certificado/{certification}', 'show')->name('events.certifications.show');
                    Route::get('/editar-certificado/{certification}', 'edit')->name('events.certifications.edit');
                    Route::post('/registrar-participantes/{event}', 'store')->name('events.certifications.store');

                    Route::post('/actualizar-certificado/{certification}', 'update')->name('events.certifications.update');
                    Route::delete('/eliminar-certificado/{certification}', 'destroy')->name('events.certifications.destroy');

                    // --------- IMPORT PARTICIPANTS -------------
                    Route::get('/descargar-plantilla-registro-masivo-participantes', 'downloadImportTemplate')->name('events.certifications.download.participants.template');
                    Route::post('/registro-masivo-de-participantes/{event}', 'storeMassive')->name('events.certifications.store.massive');

                    // ---------- IMPORT SCORES -------------
                    Route::get('/descargar-plantilla-registro-de-notas-masivo', 'downloadImportScoreTemplate')->name('events.certifications.download.participants_score.template');
                    Route::post('/registro-masivo-de-notas/{event}', 'storeScoresMasive')->name('events.certifications.store.score_massive');

                    // ---------- IMPORT AREA ---------------

                    Route::get('/descargar-plantilla-registro-de-area-obervacion', 'downloadImportAreaTemplate')->name('events.certifications.download.participants_area.template');
                    Route::post('/registro-masivo-de-area-observacion/{event}', 'updateAreaMassive')->name('events.certifications.store.area_massive');

                    // ----------- RESET -----------------
                    Route::post('/reiniciar-certificado/{certification}', 'reset')->name('events.certifications.reset');
                });
            });

            // -------------- WEBINAR ---------------

            // ------ admin.webinars.*
            Route::group(['prefix' => 'webinar', 'as' => 'webinars.'], function () {

                // ------ admin.webinars.all.*
                Route::group(['prefix' => 'general', 'as' => 'all.'], function () {

                    Route::controller(WebinarController::class)->group(function () {

                        Route::get('/', 'index')->name('index');
                        Route::get('/ver/{webinar}', 'show')->name('show');
                        Route::get('/editar/{webinar}', 'edit')->name('edit');
                        Route::post('/registrar', 'store')->name('store');
                        Route::post('/actualizar/{webinar}', 'update')->name('update');
                        Route::delete('/eliminar/{webinar}', 'destroy')->name('destroy');

                        Route::get('/exportar-excel', 'exportExcel')->name('exportExcel');

                        // ---------- FILES ---------

                        Route::group(['prefix' => 'archivos', 'as' => 'files.'], function () {

                            Route::get('/{webinar}', 'getFilesDataTable')->name('index');
                            Route::get('/descargar-archivo/{file}', 'downloadFile')->name('download');
                            Route::post('/registrar/{webinar}', 'storeFiles')->name('store');
                            Route::delete('/eliminar-archivo/{webinar}/{file}', 'destroyFile')->name('destroy');
                        });
                    });

                    // *

                    Route::group(['prefix' => 'eventos', 'as' => 'events.'], function () {

                        Route::controller(WebinarEventController::class)->group(function () {

                            Route::get('/listar/{webinar}', 'index')->name('index');
                            Route::get('/crear', 'create')->name('create');
                            Route::get('/ver/{webinarEvent}', 'show')->name('show');
                            Route::get('/editar/{webinarEvent}', 'edit')->name('edit');
                            Route::post('/registrar/{webinar}', 'store')->name('store');
                            Route::post('/actualizar/{webinarEvent}', 'update')->name('update');
                            Route::delete('/eliminar/{webinarEvent}', 'destroy')->name('destroy');
                        });

                        Route::group(['prefix' => 'certificados', 'as' => 'certifications.'], function () {

                            Route::controller(WebinarEventCertificationsController::class)->group(function () {

                                Route::get('/obtener-certificados-internos/{webinarEvent}', 'index')->name('index');
                                Route::get('/obtener-usuarios/{webinarEvent}', 'getUsersList')->name('getUsersList');
                                Route::post('/registrar-participantes/{webinarEvent}', 'store')->name('store');
                                Route::post('/actualizar-desbloqueo/{webinarCertification}', 'updateUnlock')->name('updateUnlock');
                                Route::delete('/eliminar-participante/{webinarCertification}', 'destroy')->name('destroy');
                            });
                        });
                    });
                });
            });

            // --------------- FORGETTING CURVE --------------

            // *

            Route::group(['prefix' => 'curva-del-olvido', 'as' => 'forgettingCurve.'], function () {

                Route::controller(ForgettingCurveController::class)->group(function () {

                    Route::get('/', 'index')->name('index');
                    Route::get('/ver/{forgettingCurve}', 'show')->name('show');
                    Route::get('/editar/{forgettingCurve}', 'edit')->name('edit');
                    Route::get('/obtener-cursos-regulares', 'getCourses')->name('getCourses');
                    Route::get('/obtener-pasos/{instance}', 'getDatatableSteps')->name('getDatatableSteps');
                    Route::post('/registrar', 'store')->name('store');
                    Route::post('/actualizar/{forgettingCurve}', 'update')->name('update');
                    Route::delete('/eliminar/{forgettingCurve}', 'destroy')->name('destroy');

                    Route::get('/exportar-excel', 'exportExcel')->name('exportExcel');
                });

                Route::group(['prefix' => 'pasos', 'as' => 'steps.'], function () {

                    Route::controller(CurveStepController::class)->group(function () {

                        Route::get('/ver/{step}', 'show')->name('show');
                        Route::get('/edit/{step}', 'edit')->name('edit');
                        Route::get('/obtener-examen/{exam}', 'editExam')->name('editExam');
                        Route::post('/registrar-examen/{step}', 'registerExam')->name('registerExam');
                        Route::post('/actualizar/{step}', 'update')->name('update');
                        Route::post('/actualizar-examen/{exam}', 'updateExam')->name('updateExam');
                        Route::delete('/eliminar-examen/{exam}', 'deleteExam')->name('deleteExam');
                    });

                    Route::group(['prefix' => 'evaluaciones', 'as' => 'evaluation.'], function () {

                        Route::controller(AdminCurveQuestionController::class)->group(function () {
                            Route::get('/ver/{exam}', 'index')->name('showQuestions');
                            Route::get('/ver-enunciado/{question}', 'show')->name('questions.show');
                            Route::get('/obtener-tipo-de-enunciado', 'getQuestionType')->name('questions.getType'); // -------
                            Route::post('/registrar-enunciado/{exam}', 'store')->name('questions.store');
                            Route::post('/actualizar-enunciado/{question}', 'update')->name('questions.update');
                            Route::post('/eliminar-enunciado/{question}', 'destroy')->name('questions.destroy');
                        });
                    });

                    Route::group(['prefix' => 'video', 'as' => 'video.'], function () {

                        Route::controller(FcVideoController::class)->group(function () {

                            Route::post('/subir-video/{step}', 'upload')->name('upload');
                            Route::delete('/eliminar-video/{video}', 'destroy')->name('destroy');
                        });

                        Route::group(['prefix' => 'preguntas-de-video', 'as' => 'questionVideo.'], function () {

                            Route::controller(FcVideoQuestionController::class)->group(function () {

                                Route::get('/obtener-enunciados/{video}', 'getDatatable')->name('getDatatable');
                                Route::get('/{question}', 'show')->name('show'); // * ---
                                Route::post('/registrar-enunciado/{video}', 'store')->name('store');
                                Route::post('/actualizar-enunciado/{question}', 'update')->name('update');
                                Route::delete('/eliminar-enunciado/{question}', 'destroy')->name('destroy');
                            });
                        });
                    });
                });
            });

            // --------------- FORGETTING CURVE REPORT--------------

            Route::group(['prefix' => 'reporte-de-la-curva-del-olvido', 'as' => 'reportForgettingCurve.'], function () {

                Route::controller(ReportForgettingCurveController::class)->group(function () {

                    Route::get('/', 'index')->name('index');
                    Route::get('/exportar-excel', 'exportExcel')->name('exportExcel');
                });
            });

            // --------------- ANNOUNCEMENTS ----------------

            Route::group(['prefix' => 'anuncios'], function () {

                Route::controller(AdminAnnouncementsController::class)->group(function () {

                    Route::get('/', 'index')->name('announcements.index');
                    Route::get('/editar-banner/{banner}', 'editBanner')->name('announcements.banner.edit');
                    Route::post('/registrar-banner', 'storeBanner')->name('announcements.banner.store');
                    Route::post('/actualizar-banner/{banner}', 'updateBanner')->name('announcements.banner.update');
                    Route::delete('/banner/eliminar/{banner}', 'destroyBanner')->name('announcements.banner.delete');

                    Route::get('/editar-publicación/{card}', 'editCard')->name('announcements.card.edit');
                    Route::post('/registrar-publicación', 'storeCard')->name('announcements.card.store');
                    Route::post('/actualizar-publicación/{card}', 'updateCard')->name('announcements.card.update');
                    Route::delete('/publicación/eliminar/{card}', 'destroyCard')->name('announcements.card.delete');
                });
            });


            // --------------- SLIDER LOGIN ----------------

            Route::group(['prefix' => 'slider-images'], function () {

                Route::controller(AdminSliderImageController::class)->group(function () {

                    // Route::get('/', 'index')->name('sliderImages.index');
                    Route::get('/editar/{sliderImage}', 'edit')->name('sliderImage.edit');
                    Route::post('/registrar-sliderImage', 'store')->name('sliderImage.store');
                    Route::post('/actualizar-sliderImage/{sliderImage}', 'update')->name('sliderImage.update');
                    Route::delete('/eliminar-sliderImage/{sliderImage}', 'destroy')->name('sliderImage.destroy');
                });
            });


            Route::group(['prefix' => 'noticias', 'as' => 'news.'], function () {

                Route::controller(NewsController::class)->group(function () {

                    Route::get('/editar-noticia/{new}', 'edit')->name('edit');
                    Route::post('/registrar-noticia', 'store')->name('store');
                    Route::post('/actualizar-noticia/{new}', 'update')->name('update');
                    Route::delete('/eliminar-noticia/{new}', 'destroy')->name('destroy');
                });
            });


            // --------------- SURVEYS ----------------

            Route::group(['prefix' => 'encuestas'], function () {

                Route::controller(AdminSurveyController::class)->group(function () {

                    Route::get('/', 'index')->name('surveys.all.index');
                    Route::get('/editar/{survey}', 'edit')->name('surveys.all.edit');
                    Route::get('/ver/{survey}', 'show')->name('surveys.all.show');
                    Route::post('/registrar', 'store')->name('surveys.all.store');
                    Route::post('/actualizar/{survey}', 'update')->name('surveys.all.update');
                    Route::delete('/eliminar/{survey}', 'destroy')->name('surveys.all.destroy');
                });

                Route::group(['prefix' => 'grupos'], function () {

                    Route::controller(AdminSurveyGroupController::class)->group(function () {

                        Route::get('/{survey}', 'index')->name('surveys.all.groups.index');
                        Route::get('/ver/{group}', 'show')->name('surveys.all.groups.show');
                        Route::get('/editar/{group}', 'edit')->name('surveys.all.groups.edit');
                        Route::post('/{survey}/registrar', 'store')->name('surveys.all.groups.store');
                        Route::post('/actualizar/{group}', 'update')->name('surveys.all.groups.update');
                        Route::delete('/eliminar/{group}', 'destroy')->name('surveys.all.groups.desrtoy');
                    });

                    Route::group(['prefix' => 'preguntas'], function () {

                        Route::controller(AdminSurveyStatementController::class)->group(function () {

                            Route::get('/{group}', 'index')->name('surveys.all.groups.statements.index');
                            Route::get('/obtener-tipo-de-pregunta/{group}', 'getStatementType')->name('surveys.all.groups.statements.getType');
                            Route::get('/ver-pregunta/{statement}', 'show')->name('surveys.all.groups.statements.show');
                            Route::post('/{group}/registrar', 'store')->name('surveys.all.groups.statement.store');
                            Route::post('/actualizar/{statement}', 'update')->name('surveys.all.groups.statements.update');
                            Route::delete('/eliminar/{statement}', 'destroy')->name('surveys.all.groups.statement.destroy');
                        });

                        Route::group(['prefix' => 'opciones'], function () {

                            Route::controller(AdminSurveyOptionController::class)->group(function () {
                                Route::delete('/eliminar/{option}', 'destroy')->name('surveys.all.groups.statement.options.destroy');
                            });
                        });
                    });
                });

                Route::group(['prefix' => 'reporte-perfil-de-usuario'], function () {

                    Route::controller(ProfileSurveyReportController::class)->group(function () {

                        Route::get('/', 'index')->name('surveys.reports.profile.index');
                        Route::get('/descargar-excel-perfil-de-usuario', 'downloadExcelProfile')->name('download.excel.profile');
                    });
                });

                Route::group(['prefix' => 'reporte-encuestados'], function () {

                    Route::controller(SurveysReportController::class)->group(function () {

                        Route::get('/', 'index')->name('surveys.reports.index');
                        Route::get('/descargar-excel-encuestas-usuario', 'downloadExcelUserSurveys')->name('download.excel.user.surveys');
                        Route::delete('/eliminar-encuesta-de-usuario/{userSurvey}', 'destroy')->name('surveys.reports.delete');
                    });
                });
            });

            // -------------- FILES MANAGEMENT -------------

            // ----------- admin.filesManagement.* -----------
            Route::group(['prefix' => 'archivos', 'as' => 'filesManagement.'], function () {

                Route::controller(FileController::class)->group(function () {

                    Route::get('/', 'index')->name('index');
                    Route::get('/descargar-archivo/{file}', 'downloadFile')->name('download');
                    Route::post('/almacenar-archivo', 'storeFile')->name('store');
                    Route::delete('/eiminar-archivo/{file}', 'destroyFile')->name('destroy');
                });
            });
        });

        // ----------- CERTIFICATIONS MODULE ------------

        // Route::group(['prefix' => 'certificados'], function () {

        //     Route::controller(AdminCertificationsController::class)->group(function () {

        //         Route::get('/', 'index')->name('certifications.index');
        //     });
        //     // ----------------- PDFS ------------------

        //     // ------ Certifications ------------
        //     //-------  pdf.certification.* ----------
        //     Route::group(['as' => 'pdf.certification.'], function () {

        //         Route::controller(PdfCertificationController::class)->group(function () {

        //             Route::get('/generar-pdf-evaluación-de-participante/{certification}', 'examPdf')->name('exam');
        //             Route::get('/generar-pdf-anexo/{certification}/unidad-minera/{miningUnit}', 'anexoPdf')->name('anexo');
        //         });
        //     });
        // });
    });

    // -------  RUTAS DE LA INTERFAZ AULA ---------------

    Route::group(['middleware' => 'aula', 'prefix' => 'aula', 'as' => 'aula.'], function () {

        // -------------- GENERAL --------------------

        Route::get('/inicio', [AulaHomeController::class, 'index'])->name('index');

        Route::controller(AulaProfileController::class)->group(function () {

            Route::group(['prefix' => 'perfil', 'as' => 'profile.'], function () {

                Route::get('/', 'index')->name('index');
                Route::get('/editar-avatar/{user}', 'editUserAvatar')->name('userAvatar.edit');
                Route::post('/actualizar-avatar/{user}', 'updateUserAvatar')->name('updateUserAvatar');
                Route::post('/actualizar-contraseña/{user}', 'updatePassword')->name('updatePassword');

                // Route::group(['middleware' => 'check.role:instructor'], function () {
                //     Route::get('/información-como-instructor', 'getInformation')->name('instructor.information.index');
                //     Route::get('/editar-información-como-instructor', 'editInformation')->name('instructor.information.edit');
                //     Route::post('/actualizar-información', 'updateInformation')->name('instructor.information.update');
                // });
            });
        });


        // Route::group(['middleware' => 'check.role:supervisor'], function () {

        //     // -------------- EVENTS SUPERVISOR --------------------


        //     Route::group(['prefix' => 'eventos', 'as' => 'supervisor.events.'], function () {

        //         Route::controller(SupervisorEventsController::class)->group(function () {
        //             Route::get('/', 'index')->name('index');
        //             Route::get('/ver/{event}', 'show')->name('show');
        //             Route::get('/ver-certificado/{certification}', 'showCertification')->name('showCertification');
        //         });
        //     });

        //     // -------------- CERTIFICATIONS SUPERVISOR --------------------

        //     Route::group(['prefix' => 'certificaciones', 'as' => 'certification.'], function () {

        //         Route::controller(CertificationSupervisorController::class)->group(function () {
        //             Route::get('/', 'index')->name('index');
        //             Route::get('/obtener-certificaciones', 'getCertificationDocuments')->name('getCertificationDocuments');
        //         });
        //     });

        //     // -------------- KPIS SUPERVISOR --------------------

        //     Route::group(['prefix' => 'indicador-clave-de-rendimiento', 'as' => 'supervisor.kpi.'], function () {

        //         Route::controller(SupervisorKpiController::class)->group(function () {
        //             Route::get('/', 'index')->name('index');
        //             Route::get('/obtener-data-satisfaccion', 'getSatisfactionKpi')->name('getSatisfactionKpi');
        //         });
        //     });
        // });


        Route::group([
            'middleware' => 'check.role:participants,instructor,security_manager,security_manager_admin,companies,supervisor',
        ], function () {

            // ---------------  E LEARNING -------------------

            Route::group(['prefix' => 'e-learning', 'as' => 'course.'], function () {

                Route::controller(AulaCourseController::class)->group(function () {

                    Route::get('/', 'index')->name('index');
                    Route::get('/{course}', 'show')->name('show');
                });

                // ---------- CONTENIDO ----------------

                Route::controller(AulaFolderController::class)->group(function () {

                    Route::get('/{course}/carpetas', 'index')->name('folder.index');
                    Route::get('/{course}/carpeta/{folder}', 'show')->name('folder.show');

                    Route::get('/carpeta/descargar/{file}', 'downloadFile')->name('file.download');
                });

                // -------------- CURSO EN VIVO ------------------

                Route::controller(AulaOnlineLessonController::class)->group(function () {

                    Route::get('/{course}/curso-online', 'index')->name('onlinelesson.index');
                    Route::get('/clase-online/{event}', 'show')->name('onlinelesson.show');
                });


                // -------------- INSTRUCTOR ------------------

                Route::group(['middleware' => 'check.role:participants'], function () {

                    Route::controller(AulaInstructorInformationController::class)->group(function () {
                        Route::get('/{course}/instructor/{instructor}', 'index')->name('instructor.information.index');
                    });
                });
            });

            // -------------- FIRMA DIGITAL ------------------
            //------- aula.signatures.* -------------
            Route::group(['prefix' => 'firma-digital', 'as' => 'signatures.'], function () {

                Route::controller(AulaSignaturesController::class)->group(function () {

                    Route::get('/', 'index')->name('index');
                    Route::get('/crear-firma', 'create')->name('create');
                    Route::post('/registrar-firma', 'store')->name('store');
                });
            });

            // -------------- SPEC COURSES ----------------inicio
            // ---- aula.specCourses.* -----------
            Route::group([
                'prefix' => 'cursos-de-especialización',
                'as' => 'specCourses.',
                'middleware' => 'check.role:participants,instructor'
            ], function () {

                Route::controller(AulaSpecCourseController::class)->group(function () {

                    Route::get('/', 'index')->name('index');
                    Route::get('/ver/{specCourse}', 'show')->name('show');
                    Route::get('/obtener-archivos-de-modulo/{module}', 'getModuleFiles')->name('getModuleFiles');
                });


                // ---- aula.specCourses.assignments.* -----------
                Route::group(['prefix' => 'asignaciones', 'as' => 'assignment.'], function () {

                    Route::controller(AulaSpecAssignmentController::class)->group(function () {

                        Route::get('/{specCourse}/grupos-de-eventos', 'index')->name('index');
                        Route::get('/ver-eventos/{groupEvent}', 'showEventList')->name('showEventList');
                        Route::get('/{event}/ver-asignaciones', 'show')->name('show');
                        Route::get('/obtener-información-asignación/{assignment}', 'showAssignmentInfo')->name('showAssignmentInfo');
                        Route::get('/obtener-información-asignable/{assignment}', 'showAssignable')->name('showAssignable');
                        Route::get('/descargar-archivo/{file}', 'downloadFile')->name('downloadFile');

                        Route::post('/subir-archivos-para-la-asignacion/{assignment}', 'storeAssignment')->name('storeAssignment');

                        Route::get('/obtener-información/{assignment}', 'getInfo')->name('getInfo');

                        Route::delete('/eliminar-archivo-del-participante/{file}/{assignment}', 'deleteFileParticipant')->name('deleteFileParticipant');
                    });
                });

                // ---- aula.specCourses.onlinelesson.* -----------
                Route::group([
                    'prefix' => 'clase-virtual',
                    'as' => 'onlinelesson.',
                ], function () {

                    Route::controller(AulaSpecOnlineLessonController::class)->group(function () {

                        Route::get('/{specCourse}', 'index')->name('index');
                        Route::get('/ver/{event}', 'show')->name('show');
                    });
                });

                Route::group([
                    'prefix' => 'instructor',
                    'as' => 'instructor.',
                    'middleware' => 'check.role:participants'
                ], function () {

                    Route::controller(AulaInstructorInformationController::class)->group(function () {

                        Route::get('/{instructor}/instructor/{specCourse}', 'getInformationAboutSpecCourse')->name('index');
                    });
                });


                // **** aula.specCourses.files * -----------

                Route::group([
                    'prefix' => 'archivos',
                    'as' => 'files.',
                ], function () {
                    Route::controller(AulaSpecFileController::class)->group(function () {
                        Route::get('/{specCourse}', 'index')->name('index');
                        // Route::get('/descargar-archivo/{file}', 'downloadFile')->name('download');
                        Route::get('/archivo/{file}/descargar', 'downloadFile')->name('download');
                    });
                });

                // ---- aula.specCourses.evaluations.* -----------
                Route::group([
                    'prefix' => 'evaluaciones',
                    'as' => 'evaluations.',
                    'middleware' => 'check.role:participants'
                ], function () {

                    Route::controller(AulaSpecEvaluationController::class)->group(function () {

                        Route::get('/{specCourse}', 'index')->name('index');
                    });

                    // ----  aula.specCourses.evaluations.quiz.* -----------
                    Route::group(['as' => 'quiz.'], function () {

                        Route::controller(SpecQuizController::class)->group(function () {

                            Route::get('/{certification}/pregunta/{num_question}', 'show')->name('show');
                            Route::post('/{certification}', 'start')->name('start');
                            Route::patch('/{certification}/{exam}/pregunta/{num_question}/{key}/{evaluation}', 'update')->name('update');
                        });
                    });
                });
            });


            //  ------------ WEBINARS ---------------

            Route::group(['prefix' => 'webinars', 'as' => 'webinar.', 'middleware' => 'check.role:participants,instructor'], function () {

                Route::controller(AulaWebinarController::class)->group(function () {

                    Route::get('/', 'index')->name('index');
                    Route::get('/ver/{webinar}', 'show')->name('show');
                });

                Route::group(['prefix' => 'recursos', 'as' => 'resources.'], function () {

                    Route::controller(AulaWebinarResourcesController::class)->group(function () {

                        Route::get('/{webinar}', 'index')->name('index');
                        Route::get('/descargar/{file}', 'downloadFile')->name('download');
                    });
                });

                Route::group(['prefix' => 'eventos', 'as' => 'events.', 'middleware' => 'check.role:instructor'], function () {

                    Route::controller(AulaWebinarEventController::class)->group(function () {

                        Route::get('ver/{event}', 'show')->name('show');
                    });
                });


                Route::group(['prefix' => 'clase-virtual', 'as' => 'onlinelesson.'], function () {
                    Route::controller(AulaWebinarOnlineLessonController::class)->group(function () {
                        Route::get('/{webinar}', 'index')->name('index');
                        Route::get('/ver/{event}', 'show')->name('show');
                    });
                });

                Route::group(['prefix' => 'instructor', 'as' => 'instructor.', 'middleware' => 'check.role:participants'], function () {
                    Route::controller(AulaInstructorInformationController::class)->group(function () {
                        Route::get('/{instructor}/instructor/{webinar}', 'getInformationAboutWebinar')->name('index');
                    });
                });
            });


            //  ------------ FREE COURSES LIVE ---------------

            Route::group(['prefix' => 'cursos-libres-en-vivo', 'as' => 'freeCourseLive.', 'middleware' => 'check.role:participants,instructor'], function () {

                Route::controller(AulaFreeCourseLiveController::class)->group(function () {

                    Route::get('/', 'index')->name('index');
                    Route::get('/ver/{course}', 'show')->name('show');
                });

                Route::group(['prefix' => 'recursos', 'as' => 'resources.'], function () {

                    Route::controller(AulaFreeCourseLiveResourcesController::class)->group(function () {

                        Route::get('/{course}', 'index')->name('index');
                        Route::get('/descargar/{file}', 'downloadFile')->name('download');
                    });
                });

                Route::group(['prefix' => 'clase-virtual', 'as' => 'onlinelesson.'], function () {
                    Route::controller(AulaFreeCourseLiveOnlineLessonController::class)->group(function () {
                        Route::get('/{course}', 'index')->name('index');
                        Route::get('/ver/{event}', 'show')->name('show');
                    });
                });

                Route::group(['prefix' => 'instructor', 'as' => 'instructor.', 'middleware' => 'check.role:participants'], function () {
                    Route::controller(AulaInstructorInformationController::class)->group(function () {
                        Route::get('/{instructor}/instructor/{course}', 'getInformationAboutFreeCourseLive')->name('index');
                    });
                });

                Route::group(['prefix' => 'eventos', 'as' => 'events.', 'middleware' => 'check.role:instructor'], function () {

                    Route::controller(AulaFreeCourseEventController::class)->group(function () {

                        Route::get('ver/{event}', 'show')->name('show');
                        Route::get('obtener-puntuacion/{certification}', 'getScoreFin')->name('certification.getScoreFin');
                        Route::post('registrar-puntuacion/{certification}', 'storeScoreFin')->name('certification.storeScoreFin');
                    });
                });


                Route::group(['prefix' => 'evaluaciones', 'as' => 'evaluations.', 'middleware' => 'check.role:participants'], function () {

                    Route::controller(AulaFreeCourseLiveEvaluationController::class)->group(function () {

                        Route::get('/{course}', 'index')->name('index');
                    });

                    Route::group(['as' => 'quiz.'], function () {

                        Route::controller(AulaFreeCourseLiveQuizController::class)->group(function () {

                            Route::get('/{certification}/pregunta/{num_question}', 'show')->name('show');
                            Route::post('/{certification}', 'start')->name('start');
                            Route::patch('/{certification}/{exam}/pregunta/{num_question}/{key}/{evaluation}', 'update')->name('update');
                        });
                    });
                });
            });



            // ------------- PARTICIPANTE ------------------

            Route::group(['middleware' => 'check.role:participants'], function () {

                // -------- E - LEARNING -----------------

                Route::group(['prefix' => 'e-learning/alumno', 'as' => 'course.'], function () {

                    // -------------- EVALUACIONES ------------------

                    Route::get('/{course}/evaluaciones', [AulaEvaluationController::class, 'index'])->name('evaluation.index');

                    Route::get('/ajax-certification/{certification}', [AulaEvaluationController::class, 'getAjaxCertification'])->name('ajax.certification');

                    Route::get('/{certification}/pregunta/{num_question}', [QuizController::class, 'show'])->name('quiz.show');
                    Route::post('/{certification}', [QuizController::class, 'start'])->name('quiz.start');
                    Route::patch('/{certification}/{exam}/pregunta/{num_question}/{key}/{evaluation}', [QuizController::class, 'update'])->name('quiz.update');
                });

                // ------------ CURSOS LIBRES ---------------

                Route::group(['prefix' => 'cursos-libres', 'as' => 'freecourse.'], function () {

                    Route::controller(AulaFreeCourseController::class)->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/categoria/{category}', 'showCategory')->name('showCategory');
                        Route::get('/curso/{course}/{current_chapter}', 'showChapter')->name('showChapter');
                        Route::get('/descargar-archivo/{file}', 'downloadFile')->name('files.download');
                        Route::post('/iniciar/{course}', 'start')->name('start');
                        Route::post('/AjaxSavetime/{current_chapter}', 'updateProgressTime')->name('saveTime');
                        Route::patch('/actualizar/{current_chapter}/{new_chapter}/{course}', 'updateChapter')->name('update');
                    });

                    Route::group(['prefix' => 'evaluaciones', 'as' => 'evaluations.'], function () {
                        Route::controller(AulaFreeCourseEvaluationController::class)->group(function () {
                            Route::get('/{userFcEvaluation}/pregunta/{num_question}', 'show')->name('show');
                            Route::get('/obtener-informacion/{evaluation}', 'index')->name('information');
                            Route::post('/{evaluation}/{productCertification}', 'start')->name('start');
                            Route::patch('/{userFcEvaluation}/{exam}/pregunta/{num_question}/{key}/{evaluation}', 'update')->name('update');
                        });
                    });
                });


                // ---------- MI PROGESO --------------

                Route::get('/mi-progreso', [AulaMyProgressController::class, 'index'])->name('myprogress.index');

                // ------------ ENCUESTAS --------------

                Route::group(['prefix' => 'encuestas', 'as' => 'surveys.'], function () {

                    Route::controller(AulaSurveysController::class)->group(function () {

                        Route::get('/', 'index')->name('index');
                        Route::get('/iniciar/{userSurvey}', 'start')->name('start');
                        Route::get('/{user_survey}/{num_question}', 'show')->name('show');
                        Route::patch('/actualizar/{user_survey}/{group_id}', 'update')->name('update');
                    });
                });

                // -------------- FORGETTING CURVE PARTICIPANT ------------------

                Route::group(['prefix' => '/curva-del-olvido', 'as' => 'forgettingCurve.'], function () {

                    Route::controller(AulaForgettingCurveController::class)->group(function () {

                        Route::get('/', 'index')->name('index');
                        Route::get('/ver-curva-del-olvido/{forgettingCurve}/{certification}', 'show')->name('show');
                    });

                    Route::group(['prefix' => '/instancias', 'as' => 'instances.'], function () {

                        Route::controller(AulaFcInstanceController::class)->group(function () {

                            Route::get('/evaluaciones/{fcInstance}/{certification}', 'show')->name('show');
                            Route::get('/información-de-la-evaluación/{fcStep}', 'getInfoEvaluation')->name('getInfoEvaluation');
                        });

                        Route::group(['prefix' => '/evaluacion', 'as' => 'evaluations.'], function () {

                            // *

                            Route::controller(AulaFcEvaluationController::class)->group(function () {

                                Route::get('/{fcStepProgress}/pregunta/{num_question}', 'show')->name('show');
                                Route::get('/{fcStepProgress}/video/{step}', 'showVideo')->name('video.show');
                                Route::get('/respuestas-correctas/{step}/{certification}', 'alternativeCorrect')->name('alternative.correct');
                                Route::post('/{step}/{fcStepProgress}/', 'start')->name('start');

                                // update normal
                                Route::patch('/{fcStepProgress}/{exam}/pregunta/{num_question}/{key}/{evaluation}', 'update')->name('update');

                                // update video
                                // Route::patch('/{fcStepProgress}/{video}/pregunta/{num_question}/{key}/{evaluation}', 'updateVideo')->name('updateVideo');

                            });

                            Route::group(['prefix' => '/video', 'as' => 'video.'], function () {
                                Route::controller(AulaFcVideoController::class)->group(function () {

                                    Route::patch('/{fcStepProgress}/{video}/pregunta/{num_question}/{key}/{evaluation}', 'updateVideo')->name('updateVideo');
                                });
                            });
                        });
                    });
                });


                // -------------- MY DOCUMENTS  ------------------

                Route::group(['prefix' => 'mis-documentos', 'as' => 'myDocs.'], function () {
                    Route::controller(AulaDocParticipantController::class)->group(function () {

                        Route::get('/', 'index')->name('index');
                        Route::get('/descargar-documento/{file}', 'downloadFile')->name('downloadFile');
                        Route::post('/guardar-documentos', 'storeFile')->name('storeFile');
                        Route::delete('/eliminar-documento/{file}', 'destroyFile')->name('destroyDocument');
                    });
                });
            });

            // -------------- INSTRUCTOR --------------------

            // ------------ aula.*
            Route::group(['middleware' => 'check.role:instructor'], function () {

                // ------- SURVEY USERS -----------------

                Route::group(['prefix' => 'encuestas-de-usuarios', 'as' => 'userSurveysInstructor.'], function () {

                    Route::controller(AulaUserSurveyInstructorController::class)->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/exportar-excel-encuestas-perfil-de-usuario', 'downloadExcelUserProfile')->name('download.excel.user.profile');
                    });
                });

                // ------- E - LEARNING -----------------

                Route::group(['prefix' => 'e-learning/instructor', 'as' => 'course.'], function () {

                    Route::controller(AulaEventsInsController::class)->group(function () {

                        // --------- aula.course.events.instructor.* -------------
                        Route::group(['prefix' => 'curso', 'as' => 'events.instructor.'], function () {

                            Route::get('/{course}/eventos', 'index')->name('index');
                            Route::get('/evento/{event}', 'show')->name('show');
                        });
                    });
                });

                // ----------- SPEC COURSES --------------

                //---------- aula.specCourses.* -------------
                Route::group(['prefix' => 'cursos-de-especialización/instructor', 'as' => 'specCourses.'], function () {

                    Route::controller(AulaSpecCoursesInsController::class)->group(function () {

                        Route::get('/grupo-de-eventos/{groupEvent}', 'showGroupEvent')->name('showGroupEvent');
                    });

                    // ---- aula.specCourses.modules.* -----------

                    Route::group([
                        'prefix' => 'modulos',
                        'as' => 'modules.'
                    ], function () {

                        Route::controller(AulaSpecModuleController::class)->group(function () {
                            // Route::get('/{specCourse}', 'index')->name('index');
                            // Route::get('/modulo/{module}', 'show')->name('show');
                            Route::get('/modulo/ver-participantes/{event}', 'showParticipants')->name('showParticipants');
                        });
                    });

                    // *
                    // ----- aula.specCourses.assignments.* ------------
                    Route::group(['prefix' => 'asignaciones', 'as' => 'assignments.'], function () {

                        Route::controller(AulaInsAssignmentsController::class)->group(function () {

                            Route::get('/obtener-lista-de-asignables/{assignment}', 'getAssignablesList')->name('getAssignablesList');
                            Route::get('/obtener-información-de-entregable/{assignment}/{type}/{id}', 'getDataAssignable')->name('getDataAssignable');
                            Route::post('/actualizar-nota-de-asignación/{assignment}/{type}/{id}', 'updateAssignmentScore')->name('updateAssignmentScore');
                        });
                    });
                });
            });

            // --------------- SEGURIDAD --------------------
            // ------------ aula.*
            Route::group(['middleware' => 'check.role:security_manager,security_manager_admin'], function () {

                // ------- E - LEARNING -----------------

                Route::group(['prefix' => 'e-learning/seguridad', 'as' => 'course.'], function () {

                    Route::controller(AulaEventsSecurController::class)->group(function () {

                        // --------- aula.course.events.security.* -------------
                        Route::group(['prefix' => 'curso', 'as' => 'events.security.'], function () {

                            Route::get('/{course}/eventos', 'index')->name('index');
                            Route::get('/evento/{event}', 'show')->name('show');
                        });
                    });
                });

                // ----------- SIGNATURES ----------------

                Route::group([
                    'prefix' => 'firma-digital/seguridad',
                    'as' => 'signatures.security.'
                ], function () {

                    //------- aula.signatures.security.* -----------
                    Route::controller(AulaSignaturesController::class)->group(function () {

                        Route::get('/{event}/{miningUnit}', 'indexSecurity')->name('index');
                        Route::get('/{event}/{miningUnit}/crear-firma', 'createSecurity')->name('create');
                        Route::post('/{event}/{miningUnit}/registrar-firma', 'storeSecurity')->name('store');
                    });
                });
            });


            // --------------- EMPRESAS --------------------


            Route::group(['middleware' => 'check.role:companies'], function () {

                Route::group(['prefix' => 'usuarios-de-empresa', 'as' => 'userCompany.'], function () {

                    Route::controller(AulaUserCompanyController::class)->group(function () {

                        Route::get('/', 'index')->name('index');
                    });
                });
                Route::group(['prefix' => 'documentos-de-la-empresa', 'as' => 'docCompany.'], function () {
                    Route::controller(AulaDocCompanyController::class)->group(function () {

                        Route::get('/', 'index')->name('index');
                        Route::get('/descargar-documento/{file}', 'downloadFile')->name('downloadFile');
                        Route::post('/guardar-documentos', 'storeFile')->name('storeFile');
                        Route::delete('/eliminar-documento/{file}', 'destroyFile')->name('destroyDocument');
                    });
                });
                Route::group(['prefix' => 'indicadores-clave-de-rendimiento', 'as' => 'kpisCompany.'], function () {
                    Route::controller(AulaKpisCompanyController::class)->group(function () {

                        Route::get('/', 'index')->name('index');
                        Route::get('/obtener-data-satisfaccion', 'getSatisfactionKpi')->name('getSatisfactionKpi');
                    });
                });

                Route::group(['prefix' => 'evaluaciones-de-participantes', 'as' => 'userEvaluationsCompany.'], function () {
                    Route::controller(AulaUserEvaluationsCompanyController::class)->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/exportar-excel-evaluaciones-participantes', 'downloadExcelEvaluations')->name('download.excel.userEvaluations');
                    });
                });
            });
        });
    });

    // ---------- ACTUALIZAR ASISTENCIA -----------

    // Route::group(['middleware' => 'check.role:admin,super_admin,technical_support,instructor'], function () {

    //     Route::controller(AdminCertificationsController::class)->group(function () {

    //         Route::post('/actualizar-asistencia/{certification}', 'updateAssist')->name('events.certification.updateAssist');
    //     });
    // });
});
