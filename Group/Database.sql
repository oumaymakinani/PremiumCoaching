PGDMP  9                    }           Coaching    17.4    17.0 6    [           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            \           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            ]           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false            ^           1262    16388    Coaching    DATABASE     l   CREATE DATABASE "Coaching" WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'C';
    DROP DATABASE "Coaching";
                     postgres    false            �            1259    16518    bookings    TABLE     �  CREATE TABLE public.bookings (
    booking_id integer NOT NULL,
    user_id integer,
    session_id integer,
    status character varying(20) DEFAULT 'pending'::character varying,
    booked_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT bookings_status_check CHECK (((status)::text = ANY ((ARRAY['confirmed'::character varying, 'pending'::character varying, 'cancelled'::character varying])::text[])))
);
    DROP TABLE public.bookings;
       public         heap r       postgres    false            �            1259    16517    bookings_booking_id_seq    SEQUENCE     �   CREATE SEQUENCE public.bookings_booking_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE public.bookings_booking_id_seq;
       public               postgres    false    222            _           0    0    bookings_booking_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE public.bookings_booking_id_seq OWNED BY public.bookings.booking_id;
          public               postgres    false    221            �            1259    16574    coach_availability    TABLE     �  CREATE TABLE public.coach_availability (
    availability_id integer NOT NULL,
    coach_id integer,
    available_date date NOT NULL,
    start_time time without time zone NOT NULL,
    end_time time without time zone NOT NULL,
    status character varying(20) DEFAULT 'available'::character varying,
    CONSTRAINT coach_availability_status_check CHECK (((status)::text = ANY ((ARRAY['available'::character varying, 'booked'::character varying])::text[])))
);
 &   DROP TABLE public.coach_availability;
       public         heap r       postgres    false            �            1259    16573 &   coach_availability_availability_id_seq    SEQUENCE     �   CREATE SEQUENCE public.coach_availability_availability_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 =   DROP SEQUENCE public.coach_availability_availability_id_seq;
       public               postgres    false    226            `           0    0 &   coach_availability_availability_id_seq    SEQUENCE OWNED BY     q   ALTER SEQUENCE public.coach_availability_availability_id_seq OWNED BY public.coach_availability.availability_id;
          public               postgres    false    225            �            1259    16486    coaches    TABLE     :  CREATE TABLE public.coaches (
    coach_id integer NOT NULL,
    user_id integer,
    specialization character varying(100) NOT NULL,
    experience_years integer NOT NULL,
    certification character varying(100),
    bio text,
    profile_image character varying(255) DEFAULT 'default.jpg'::character varying
);
    DROP TABLE public.coaches;
       public         heap r       postgres    false            �            1259    16485    coaches_coach_id_seq    SEQUENCE     �   CREATE SEQUENCE public.coaches_coach_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 +   DROP SEQUENCE public.coaches_coach_id_seq;
       public               postgres    false    220            a           0    0    coaches_coach_id_seq    SEQUENCE OWNED BY     M   ALTER SEQUENCE public.coaches_coach_id_seq OWNED BY public.coaches.coach_id;
          public               postgres    false    219            �            1259    16538    feedback    TABLE     =  CREATE TABLE public.feedback (
    feedback_id integer NOT NULL,
    user_id integer,
    coach_id integer,
    rating integer NOT NULL,
    comments text NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT feedback_rating_check CHECK (((rating >= 1) AND (rating <= 5)))
);
    DROP TABLE public.feedback;
       public         heap r       postgres    false            �            1259    16537    feedback_feedback_id_seq    SEQUENCE     �   CREATE SEQUENCE public.feedback_feedback_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE public.feedback_feedback_id_seq;
       public               postgres    false    224            b           0    0    feedback_feedback_id_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE public.feedback_feedback_id_seq OWNED BY public.feedback.feedback_id;
          public               postgres    false    223            �            1259    16589    training_sessions    TABLE     �  CREATE TABLE public.training_sessions (
    session_id integer NOT NULL,
    coach_id integer,
    title character varying(255) NOT NULL,
    description text,
    difficulty character varying(50),
    session_type character varying(50),
    duration integer NOT NULL,
    likes integer DEFAULT 0,
    CONSTRAINT training_sessions_difficulty_check CHECK (((difficulty)::text = ANY ((ARRAY['Beginner'::character varying, 'Intermediate'::character varying, 'Advanced'::character varying])::text[]))),
    CONSTRAINT training_sessions_session_type_check CHECK (((session_type)::text = ANY ((ARRAY['One-on-One'::character varying, 'Group Training'::character varying])::text[])))
);
 %   DROP TABLE public.training_sessions;
       public         heap r       postgres    false            �            1259    16588     training_sessions_session_id_seq    SEQUENCE     �   CREATE SEQUENCE public.training_sessions_session_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 7   DROP SEQUENCE public.training_sessions_session_id_seq;
       public               postgres    false    228            c           0    0     training_sessions_session_id_seq    SEQUENCE OWNED BY     e   ALTER SEQUENCE public.training_sessions_session_id_seq OWNED BY public.training_sessions.session_id;
          public               postgres    false    227            �            1259    16475    users    TABLE     �  CREATE TABLE public.users (
    user_id integer NOT NULL,
    full_name character varying(100) NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(255) NOT NULL,
    phone character varying(20) NOT NULL,
    role character varying(10) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['player'::character varying, 'coach'::character varying])::text[])))
);
    DROP TABLE public.users;
       public         heap r       postgres    false            �            1259    16474    users_user_id_seq    SEQUENCE     �   CREATE SEQUENCE public.users_user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.users_user_id_seq;
       public               postgres    false    218            d           0    0    users_user_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.users_user_id_seq OWNED BY public.users.user_id;
          public               postgres    false    217            �           2604    16521    bookings booking_id    DEFAULT     z   ALTER TABLE ONLY public.bookings ALTER COLUMN booking_id SET DEFAULT nextval('public.bookings_booking_id_seq'::regclass);
 B   ALTER TABLE public.bookings ALTER COLUMN booking_id DROP DEFAULT;
       public               postgres    false    222    221    222            �           2604    16577 "   coach_availability availability_id    DEFAULT     �   ALTER TABLE ONLY public.coach_availability ALTER COLUMN availability_id SET DEFAULT nextval('public.coach_availability_availability_id_seq'::regclass);
 Q   ALTER TABLE public.coach_availability ALTER COLUMN availability_id DROP DEFAULT;
       public               postgres    false    225    226    226            �           2604    16489    coaches coach_id    DEFAULT     t   ALTER TABLE ONLY public.coaches ALTER COLUMN coach_id SET DEFAULT nextval('public.coaches_coach_id_seq'::regclass);
 ?   ALTER TABLE public.coaches ALTER COLUMN coach_id DROP DEFAULT;
       public               postgres    false    220    219    220            �           2604    16541    feedback feedback_id    DEFAULT     |   ALTER TABLE ONLY public.feedback ALTER COLUMN feedback_id SET DEFAULT nextval('public.feedback_feedback_id_seq'::regclass);
 C   ALTER TABLE public.feedback ALTER COLUMN feedback_id DROP DEFAULT;
       public               postgres    false    224    223    224            �           2604    16592    training_sessions session_id    DEFAULT     �   ALTER TABLE ONLY public.training_sessions ALTER COLUMN session_id SET DEFAULT nextval('public.training_sessions_session_id_seq'::regclass);
 K   ALTER TABLE public.training_sessions ALTER COLUMN session_id DROP DEFAULT;
       public               postgres    false    228    227    228            �           2604    16478    users user_id    DEFAULT     n   ALTER TABLE ONLY public.users ALTER COLUMN user_id SET DEFAULT nextval('public.users_user_id_seq'::regclass);
 <   ALTER TABLE public.users ALTER COLUMN user_id DROP DEFAULT;
       public               postgres    false    217    218    218            R          0    16518    bookings 
   TABLE DATA           V   COPY public.bookings (booking_id, user_id, session_id, status, booked_at) FROM stdin;
    public               postgres    false    222   �H       V          0    16574    coach_availability 
   TABLE DATA           u   COPY public.coach_availability (availability_id, coach_id, available_date, start_time, end_time, status) FROM stdin;
    public               postgres    false    226   @I       P          0    16486    coaches 
   TABLE DATA           y   COPY public.coaches (coach_id, user_id, specialization, experience_years, certification, bio, profile_image) FROM stdin;
    public               postgres    false    220   �I       T          0    16538    feedback 
   TABLE DATA           `   COPY public.feedback (feedback_id, user_id, coach_id, rating, comments, created_at) FROM stdin;
    public               postgres    false    224   �J       X          0    16589    training_sessions 
   TABLE DATA           �   COPY public.training_sessions (session_id, coach_id, title, description, difficulty, session_type, duration, likes) FROM stdin;
    public               postgres    false    228    M       N          0    16475    users 
   TABLE DATA           ]   COPY public.users (user_id, full_name, email, password, phone, role, created_at) FROM stdin;
    public               postgres    false    218    P       e           0    0    bookings_booking_id_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('public.bookings_booking_id_seq', 10, true);
          public               postgres    false    221            f           0    0 &   coach_availability_availability_id_seq    SEQUENCE SET     U   SELECT pg_catalog.setval('public.coach_availability_availability_id_seq', 12, true);
          public               postgres    false    225            g           0    0    coaches_coach_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.coaches_coach_id_seq', 6, true);
          public               postgres    false    219            h           0    0    feedback_feedback_id_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('public.feedback_feedback_id_seq', 20, true);
          public               postgres    false    223            i           0    0     training_sessions_session_id_seq    SEQUENCE SET     O   SELECT pg_catalog.setval('public.training_sessions_session_id_seq', 28, true);
          public               postgres    false    227            j           0    0    users_user_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.users_user_id_seq', 18, true);
          public               postgres    false    217            �           2606    16526    bookings bookings_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.bookings
    ADD CONSTRAINT bookings_pkey PRIMARY KEY (booking_id);
 @   ALTER TABLE ONLY public.bookings DROP CONSTRAINT bookings_pkey;
       public                 postgres    false    222            �           2606    16581 *   coach_availability coach_availability_pkey 
   CONSTRAINT     u   ALTER TABLE ONLY public.coach_availability
    ADD CONSTRAINT coach_availability_pkey PRIMARY KEY (availability_id);
 T   ALTER TABLE ONLY public.coach_availability DROP CONSTRAINT coach_availability_pkey;
       public                 postgres    false    226            �           2606    16494    coaches coaches_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.coaches
    ADD CONSTRAINT coaches_pkey PRIMARY KEY (coach_id);
 >   ALTER TABLE ONLY public.coaches DROP CONSTRAINT coaches_pkey;
       public                 postgres    false    220            �           2606    16496    coaches coaches_user_id_key 
   CONSTRAINT     Y   ALTER TABLE ONLY public.coaches
    ADD CONSTRAINT coaches_user_id_key UNIQUE (user_id);
 E   ALTER TABLE ONLY public.coaches DROP CONSTRAINT coaches_user_id_key;
       public                 postgres    false    220            �           2606    16547    feedback feedback_pkey 
   CONSTRAINT     ]   ALTER TABLE ONLY public.feedback
    ADD CONSTRAINT feedback_pkey PRIMARY KEY (feedback_id);
 @   ALTER TABLE ONLY public.feedback DROP CONSTRAINT feedback_pkey;
       public                 postgres    false    224            �           2606    16598 (   training_sessions training_sessions_pkey 
   CONSTRAINT     n   ALTER TABLE ONLY public.training_sessions
    ADD CONSTRAINT training_sessions_pkey PRIMARY KEY (session_id);
 R   ALTER TABLE ONLY public.training_sessions DROP CONSTRAINT training_sessions_pkey;
       public                 postgres    false    228            �           2606    16484    users users_email_key 
   CONSTRAINT     Q   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);
 ?   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_key;
       public                 postgres    false    218            �           2606    16482    users users_pkey 
   CONSTRAINT     S   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (user_id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public                 postgres    false    218            �           2606    16527    bookings bookings_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.bookings
    ADD CONSTRAINT bookings_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(user_id) ON DELETE CASCADE;
 H   ALTER TABLE ONLY public.bookings DROP CONSTRAINT bookings_user_id_fkey;
       public               postgres    false    218    3497    222            �           2606    16582 3   coach_availability coach_availability_coach_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.coach_availability
    ADD CONSTRAINT coach_availability_coach_id_fkey FOREIGN KEY (coach_id) REFERENCES public.coaches(coach_id) ON DELETE CASCADE;
 ]   ALTER TABLE ONLY public.coach_availability DROP CONSTRAINT coach_availability_coach_id_fkey;
       public               postgres    false    220    226    3499            �           2606    16497    coaches coaches_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.coaches
    ADD CONSTRAINT coaches_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(user_id) ON DELETE CASCADE;
 F   ALTER TABLE ONLY public.coaches DROP CONSTRAINT coaches_user_id_fkey;
       public               postgres    false    3497    220    218            �           2606    16553    feedback feedback_coach_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.feedback
    ADD CONSTRAINT feedback_coach_id_fkey FOREIGN KEY (coach_id) REFERENCES public.coaches(coach_id) ON DELETE CASCADE;
 I   ALTER TABLE ONLY public.feedback DROP CONSTRAINT feedback_coach_id_fkey;
       public               postgres    false    224    220    3499            �           2606    16548    feedback feedback_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.feedback
    ADD CONSTRAINT feedback_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(user_id) ON DELETE CASCADE;
 H   ALTER TABLE ONLY public.feedback DROP CONSTRAINT feedback_user_id_fkey;
       public               postgres    false    3497    218    224            �           2606    16599 1   training_sessions training_sessions_coach_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.training_sessions
    ADD CONSTRAINT training_sessions_coach_id_fkey FOREIGN KEY (coach_id) REFERENCES public.coaches(coach_id) ON DELETE CASCADE;
 [   ALTER TABLE ONLY public.training_sessions DROP CONSTRAINT training_sessions_coach_id_fkey;
       public               postgres    false    3499    228    220            R   �   x���=A�z8�^@��0p;5����y�A[���q���sSQ?�0E!
=u�7����f98� �����P.<4$&ş���y`:�ck	J�;����O�G�,���I��m���W+u�Ü�LDo�kF}      V   i   x�e�;� D�z؋���'��j�/�(�d�)Nq#T�Ob'.�[E�@��|��f�iC�q�	��Z�4ʁRz��m֭�o��Y~��e��[I��`��s�_9�      P   �   x��ϱN�0���7u��BIӒN�B"l,�r��1��9�"$FF���>�Jh�D1d��Ҍ�Kt<MP,��W��G1�&��c�QƟNO���'s*��ְ�瑹�uU/V�I՝����@����ӥy�7BV>yJB�Dx�D��1l9Pʮ��b��=l�Z�y��:��m���b���ae��a�F��Zm�4E���?��.^���QB����8�G�;X�9<��q����KsE���k�e�R処      T   I  x��T�r�0]�_q�o2��J��LKɆ0�a��7�@��$����JnJa�3�F::���%�X����y���u� �]��z��r�YŊ"m��oe���{e�
>�
9 �:i���gt�0�}R�p����.���T-��*�Y�	�bE��)��<N��'���F�N�5B��t���J�"r3�+�>���`��ll Z@s�ȓ��Qʀ�"f{��0A��d:��+؏'g�Do�@@9�sB�mX���&pv�LbVl���a��&��8�H�&�OV�����b6o3)�H����H4�:��L2=��Ì)�j����d�-�A�M7��Hq
<<!���\��|# "�$��:��G4=�+��B�ڀ�4�@�捦�$�`w�Zlǈ�������e�4$M�'L�^�D��d�8#��e��P4�c
A��u#���1�$���%�eji��%���I������S&P\2^���^�ޛ��r{�� *��ua��e��,����'*TuwU�0T����B�oR���7k!/�r�U�M�-�j�qj���dG2��z;� �;^v���.ox�m�e��A`�      X   �  x��V�n�0<�_�'�j�r�<&MZ�4hr셦�a�TH*���]R�l�sh
p ѳ;�3K/ٌ=s��
߳���k�)��c�-���ak,2�JT)Z7nϲ��T%<[.��[L�d�"�[ܢv��E��K����5�(m_��T��)��I/M��{���J��~h=�6�~2asv��ʆ��ﵰ��z^H�#,j���dD�+�P�'ց@2e��@]N�0���K4J�B:�6�p����o�ݠQ�dN�<��G�ݱ���������C �E��;���ă��J���K�0�Z��D�{��p�@������WZI�]<%{+����B�B21�̤�6:���I3��lT��d���0aÕ"�[����#�e�)��,~0����s�Z���Ѽ8�K,�J���T5��Ɲ&=s��Þ5:Bf�9�Jx�7RI��&1��f�I�4*}��)[�K�#p$K B�'�ư��W�ض=��M'p��>�eğ}b��u@\����I
,��tn��|��\ED�r즒*m�Q�@��#
[�~d��{�_�q���T�:�=6����{�	�KE�94��b�d}��?������y,�p����t�E�ʥ1�F�.�:�=��
ߺ]VC8�mV�!u��蜒�~���s�Y/�.+���[�nvv��Z	E��<��"#�{wQ��f�R�iE���4�|'W�|�K�����%y��&��\t8��օ�[�����,���
S�      N   J  x���I��JF��+X�{a����R-��ۤ��L����}muX��"oD�9��erI�,W8��I�$R��X���.qB/�XX�m��T��2�eW��c�D���F�?HF�T��*\ՠt�"a��
$�dkD�)p�TR�^�F"sE$7��/��{�n����ot�G�&�"Q�'¶o��ҟ����5�|��?���"7�M�K��^�oN(���q��C��H�)�B��] �E�~BR�������"yo�G.���dSc�Y24>r���?��T��iuC0۲�4�����P��X��"�0ů]Z# UE�ZBX2���g�Ⱥ|��
=k�G�K��u��s�"�q�C�8MG3��`s�1r���5�9�͜-S"�(\�+��P�T��Ɣ"���B��4���{��,Gq�9�p����&Q���oO��p��̮n�v�������UMZ���/�Z�5J�D��PI�]:���+'���H\vUz�ۤ���Ak?u�΍�L��G{<������C}ݱ�Y���{�~�e�*���q�r�2�P�$�>+9~�~��s�Z56�`�/���4����dxR`�_�*��j�hػ�3��¡��g.CXC�F�T���{ڃ,���5:��c��հ�j�Ϻb,{}�0�>2cП6<lXօ�7�t�.�P�<ͯ�B��	��
�VB���ܼ��c�1��vuКՙMZ���Nxnc=�m퍗�����h����y�W�c#O|5§;�&G��+@S(�ʩw�{�_Dixi�}?��y5�f��.d��cK��8��r҆x�|N>`�E{Uv�͉��%T�`@TB�Z��J�(��h     