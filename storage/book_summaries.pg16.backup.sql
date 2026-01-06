--
-- PostgreSQL database dump
--

\restrict l3uK4FC8YvVVMjzOa1DRmG9yZii4rbBu4amqdWrECySfVTXgbowfMlQT0hiO5W3

-- Dumped from database version 16.11 (Homebrew)
-- Dumped by pg_dump version 16.11 (Homebrew)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: pgcrypto; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pgcrypto WITH SCHEMA public;


--
-- Name: EXTENSION pgcrypto; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION pgcrypto IS 'cryptographic functions';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: book_genres; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.book_genres (
    id bigint NOT NULL,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    name text
);


--
-- Name: book_genres_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.book_genres_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: book_genres_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.book_genres_id_seq OWNED BY public.book_genres.id;


--
-- Name: books; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.books (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    title text NOT NULL,
    author text,
    status text DEFAULT 'pending_ingest'::text,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP,
    book_genre_id bigint,
    summary text,
    isbn character varying(255),
    cover_image_url text,
    publication_year integer,
    page_count integer,
    default_summary_pdf_url text,
    live boolean DEFAULT false NOT NULL
);


--
-- Name: cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: chapter_chunks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.chapter_chunks (
    id bigint NOT NULL,
    chapter_id uuid,
    content text,
    metadata jsonb,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP,
    embedding jsonb,
    chunk_index smallint
);


--
-- Name: chapter_chunks_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.chapter_chunks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: chapter_chunks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.chapter_chunks_id_seq OWNED BY public.chapter_chunks.id;


--
-- Name: chapters; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.chapters (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    book_id uuid,
    title text,
    content text,
    summary_md text,
    token_count integer,
    status text DEFAULT 'pending_chunk'::text,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP,
    embedding jsonb,
    metadata jsonb DEFAULT '{}'::jsonb NOT NULL,
    chapter_index smallint
);


--
-- Name: email_templates; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.email_templates (
    id bigint NOT NULL,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    name text,
    slug text,
    body text
);


--
-- Name: email_templates_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.email_templates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: email_templates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.email_templates_id_seq OWNED BY public.email_templates.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: faqs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.faqs (
    id uuid NOT NULL,
    question text,
    answer text,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP,
    embedding jsonb
);


--
-- Name: finder_felix_executions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finder_felix_executions (
    id bigint NOT NULL,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    execution text,
    postal_code text,
    num_results smallint
);


--
-- Name: finder_felix_executions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finder_felix_executions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finder_felix_executions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finder_felix_executions_id_seq OWNED BY public.finder_felix_executions.id;


--
-- Name: german_cities; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.german_cities (
    id bigint NOT NULL,
    state text,
    city text,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: german_cities_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.german_cities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: german_cities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.german_cities_id_seq OWNED BY public.german_cities.id;


--
-- Name: german_companies; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.german_companies (
    id bigint NOT NULL,
    company text,
    industry text,
    ceo_name text,
    phone text,
    email text,
    website text,
    address text,
    district text,
    city text,
    state text,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    analysis character varying(255),
    populated_by bigint,
    location_link text,
    updated_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP,
    exported_to_instantly boolean DEFAULT false NOT NULL,
    email_status text,
    first_contact_sent boolean DEFAULT false NOT NULL,
    is_duplicate boolean DEFAULT false NOT NULL
);


--
-- Name: german_companies_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.german_companies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: german_companies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.german_companies_id_seq OWNED BY public.german_companies.id;


--
-- Name: german_districts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.german_districts (
    id bigint NOT NULL,
    state text,
    city text,
    district text,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: german_districts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.german_districts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: german_districts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.german_districts_id_seq OWNED BY public.german_districts.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- Name: summaries; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.summaries (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    user_id bigint NOT NULL,
    book_id uuid NOT NULL,
    style character varying(255) NOT NULL,
    length character varying(255) NOT NULL,
    file_path text NOT NULL,
    tokens_spent integer,
    generation_time numeric(12,3),
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: summaries_v2; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.summaries_v2 (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    book_id uuid NOT NULL,
    summary jsonb NOT NULL,
    length character varying(255) NOT NULL,
    style character varying(255) NOT NULL,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    complete_book_summary text,
    formatted_summary jsonb,
    CONSTRAINT summaries_v2_length_check CHECK (((length)::text = ANY ((ARRAY['short'::character varying, 'medium'::character varying, 'long'::character varying])::text[]))),
    CONSTRAINT summaries_v2_style_check CHECK (((style)::text = ANY ((ARRAY['narrative'::character varying, 'bullet_points'::character varying, 'workbook'::character varying])::text[])))
);


--
-- Name: user_profiles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.user_profiles (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    user_id bigint NOT NULL,
    preferences jsonb DEFAULT '{"style": "narrative", "length": "5pg"}'::jsonb NOT NULL,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    role text DEFAULT 'user'::text NOT NULL
);


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    two_factor_secret text,
    two_factor_recovery_codes text,
    two_factor_confirmed_at timestamp(0) without time zone
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: book_genres id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.book_genres ALTER COLUMN id SET DEFAULT nextval('public.book_genres_id_seq'::regclass);


--
-- Name: chapter_chunks id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.chapter_chunks ALTER COLUMN id SET DEFAULT nextval('public.chapter_chunks_id_seq'::regclass);


--
-- Name: email_templates id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.email_templates ALTER COLUMN id SET DEFAULT nextval('public.email_templates_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: finder_felix_executions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finder_felix_executions ALTER COLUMN id SET DEFAULT nextval('public.finder_felix_executions_id_seq'::regclass);


--
-- Name: german_cities id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.german_cities ALTER COLUMN id SET DEFAULT nextval('public.german_cities_id_seq'::regclass);


--
-- Name: german_companies id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.german_companies ALTER COLUMN id SET DEFAULT nextval('public.german_companies_id_seq'::regclass);


--
-- Name: german_districts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.german_districts ALTER COLUMN id SET DEFAULT nextval('public.german_districts_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: book_genres; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.book_genres (id, created_at, name) FROM stdin;
\.


--
-- Data for Name: books; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.books (id, title, author, status, created_at, updated_at, book_genre_id, summary, isbn, cover_image_url, publication_year, page_count, default_summary_pdf_url, live) FROM stdin;
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: chapter_chunks; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.chapter_chunks (id, chapter_id, content, metadata, created_at, embedding, chunk_index) FROM stdin;
\.


--
-- Data for Name: chapters; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.chapters (id, book_id, title, content, summary_md, token_count, status, created_at, updated_at, embedding, metadata, chapter_index) FROM stdin;
\.


--
-- Data for Name: email_templates; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.email_templates (id, created_at, name, slug, body) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: faqs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.faqs (id, question, answer, created_at, embedding) FROM stdin;
\.


--
-- Data for Name: finder_felix_executions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.finder_felix_executions (id, created_at, execution, postal_code, num_results) FROM stdin;
\.


--
-- Data for Name: german_cities; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.german_cities (id, state, city, created_at) FROM stdin;
\.


--
-- Data for Name: german_companies; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.german_companies (id, company, industry, ceo_name, phone, email, website, address, district, city, state, created_at, analysis, populated_by, location_link, updated_at, exported_to_instantly, email_status, first_contact_sent, is_duplicate) FROM stdin;
\.


--
-- Data for Name: german_districts; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.german_districts (id, state, city, district, created_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_08_14_170933_add_two_factor_columns_to_users_table	1
5	2026_01_06_110000_enable_pgcrypto_extension	2
6	2026_01_06_110100_create_book_genres_table	2
7	2026_01_06_110200_create_books_table	2
8	2026_01_06_110300_create_chapters_table	2
9	2026_01_06_110400_create_chapter_chunks_table	2
10	2026_01_06_110500_create_misc_tables_part_1	2
11	2026_01_06_110600_create_german_location_tables	2
12	2026_01_06_110700_create_german_companies_table	2
13	2026_01_06_110800_create_summaries_tables	2
14	2026_01_06_110900_create_user_profiles_table	2
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
\.


--
-- Data for Name: summaries; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.summaries (id, user_id, book_id, style, length, file_path, tokens_spent, generation_time, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: summaries_v2; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.summaries_v2 (id, book_id, summary, length, style, created_at, updated_at, complete_book_summary, formatted_summary) FROM stdin;
\.


--
-- Data for Name: user_profiles; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.user_profiles (id, user_id, preferences, created_at, updated_at, role) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at) FROM stdin;
\.


--
-- Name: book_genres_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.book_genres_id_seq', 1, false);


--
-- Name: chapter_chunks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.chapter_chunks_id_seq', 1, false);


--
-- Name: email_templates_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.email_templates_id_seq', 1, false);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: finder_felix_executions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.finder_felix_executions_id_seq', 1, false);


--
-- Name: german_cities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.german_cities_id_seq', 1, false);


--
-- Name: german_companies_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.german_companies_id_seq', 1, false);


--
-- Name: german_districts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.german_districts_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 14, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.users_id_seq', 1, false);


--
-- Name: book_genres book_genres_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.book_genres
    ADD CONSTRAINT book_genres_pkey PRIMARY KEY (id);


--
-- Name: books books_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.books
    ADD CONSTRAINT books_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: chapter_chunks chapter_chunks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.chapter_chunks
    ADD CONSTRAINT chapter_chunks_pkey PRIMARY KEY (id);


--
-- Name: chapters chapters_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.chapters
    ADD CONSTRAINT chapters_pkey PRIMARY KEY (id);


--
-- Name: email_templates email_templates_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.email_templates
    ADD CONSTRAINT email_templates_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: faqs faqs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.faqs
    ADD CONSTRAINT faqs_pkey PRIMARY KEY (id);


--
-- Name: finder_felix_executions finder_felix_executions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finder_felix_executions
    ADD CONSTRAINT finder_felix_executions_pkey PRIMARY KEY (id);


--
-- Name: german_cities german_cities_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.german_cities
    ADD CONSTRAINT german_cities_pkey PRIMARY KEY (id);


--
-- Name: german_companies german_companies_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.german_companies
    ADD CONSTRAINT german_companies_pkey PRIMARY KEY (id);


--
-- Name: german_districts german_districts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.german_districts
    ADD CONSTRAINT german_districts_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: summaries summaries_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.summaries
    ADD CONSTRAINT summaries_pkey PRIMARY KEY (id);


--
-- Name: summaries_v2 summaries_v2_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.summaries_v2
    ADD CONSTRAINT summaries_v2_pkey PRIMARY KEY (id);


--
-- Name: user_profiles user_profiles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_profiles
    ADD CONSTRAINT user_profiles_pkey PRIMARY KEY (id);


--
-- Name: user_profiles user_profiles_user_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_profiles
    ADD CONSTRAINT user_profiles_user_id_unique UNIQUE (user_id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: books books_book_genre_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.books
    ADD CONSTRAINT books_book_genre_id_foreign FOREIGN KEY (book_genre_id) REFERENCES public.book_genres(id);


--
-- Name: chapter_chunks chapter_chunks_chapter_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.chapter_chunks
    ADD CONSTRAINT chapter_chunks_chapter_id_foreign FOREIGN KEY (chapter_id) REFERENCES public.chapters(id);


--
-- Name: chapters chapters_book_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.chapters
    ADD CONSTRAINT chapters_book_id_foreign FOREIGN KEY (book_id) REFERENCES public.books(id);


--
-- Name: german_companies german_companies_populated_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.german_companies
    ADD CONSTRAINT german_companies_populated_by_foreign FOREIGN KEY (populated_by) REFERENCES public.finder_felix_executions(id);


--
-- Name: summaries summaries_book_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.summaries
    ADD CONSTRAINT summaries_book_id_foreign FOREIGN KEY (book_id) REFERENCES public.books(id);


--
-- Name: summaries summaries_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.summaries
    ADD CONSTRAINT summaries_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: summaries_v2 summaries_v2_book_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.summaries_v2
    ADD CONSTRAINT summaries_v2_book_id_foreign FOREIGN KEY (book_id) REFERENCES public.books(id);


--
-- Name: user_profiles user_profiles_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_profiles
    ADD CONSTRAINT user_profiles_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- PostgreSQL database dump complete
--

\unrestrict l3uK4FC8YvVVMjzOa1DRmG9yZii4rbBu4amqdWrECySfVTXgbowfMlQT0hiO5W3

